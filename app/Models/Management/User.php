<?php

namespace App\Models\Management;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Positions;
use App\Enums\Roles;
use App\Services\SecureFileService;
use App\Traits\Relations\UserRelation;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable(['name', 'email', 'password', 'username', 'profile_image', 'last_login_at', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, UserRelation;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'is_temporary_officer' => 'boolean',
            'position' => Positions::class,
        ];
    }

    public function isDeveloper(): bool
    {
        return $this->username === 'developer';
    }

    public function checkPermission(string $permission): bool
    {
        $cacheKey = "user_{$this->id}_perm_{$permission}";
        return Cache::remember($cacheKey, config('app.cache_duration'), function () use ($permission) {
            return $this->permissions()->where('code', $permission)->exists();
        });
    }

    public function uploadProfileImage(UploadedFile $profileImage): void
    {
        $path = SecureFileService::uploadSecurely(
            $profileImage,
            'profile_images',
            'local',
            'photo'
        );

        // Delee Old Profile If Exist
        if (!is_null($this->profileDocument)) {
            if (Storage::disk($this->profileDocument?->disk)->exists($this->profileDocument?->path)) {
                Storage::disk($this->profileDocument?->disk)->delete($this->profileDocument?->path);
            }
            $this->profileDocument->delete();
        }


        $document = Document::create([
            'type' => 'Profile Image',
            'owner_id' => $this->id,
            'path' => $path,
            'disk' => 'local',
            'mime_type' => $profileImage->getClientMimeType(),
            'original_name' => $profileImage->getClientOriginalName(),
        ]);

        $this->update(['profile_image' => $document->uuid]);
    }

    protected function nameWithRole(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => sprintf(
                '%s (%s)',
                $attributes['full_name'] ?? '',
                Roles::tryFrom($attributes['role'])?->label()
            ),
        );
    }

    protected function isRegionalOfficer(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => sprintf(
                '%s (%s)',
                $attributes['full_name'] ?? '',
                Roles::tryFrom($attributes['role'])?->label()
            ),
        );
    }

    public function scopeValidOfficers(Builder $query, int $divisionId): Builder
    {
        $user = auth()->user();
        $accessibleRoles = accessibleRoles($user->role);

        return $query->whereIn('role', $accessibleRoles)
            ->where(function (Builder $q) use ($divisionId): void {
                $q->where(function (Builder $q2) use ($divisionId): void {
                    $q2->whereIn('role', ['region_staff', 'district_staff'])
                        ->where('division_id', $divisionId);
                })
                    ->orWhereNotIn('role', ['region_staff', 'district_staff']);
            });
    }

    protected function isStaff(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => in_array(
                $this->role,
                ['region_staff', 'district_staff'],
                true
            ),
        );
    }

    protected function isDistrict(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => in_array(
                $this->role,
                ['district_staff'],
                true
            ),
        );
    }

    protected function isRegion(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => in_array(
                $this->role,
                ['region_staff'],
                true
            ),
        );
    }

    protected function isHqStaff(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => in_array(
                $this->role,
                ['dd', 'ad', 'bc'],
                true
            ),
        );
    }
}
