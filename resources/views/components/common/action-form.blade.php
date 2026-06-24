@props([
    'id' => '',
    'route' => '',
    'btnLabel' => 'Submit',
    'label' => 'Comment Action',
    'defaultText' => '',
    'validOfficers' => [],
    'status' => null,
    'defaultDescription' => 'အထက်ပါအကြောင်းအရာပါကိစ္စနှင့်ပတ်သတ်၍ပဲခူးတိုင်းဒေသကြီးအစားအသောက်နှင့်တိုင်းရုံးဆေးဝါးကွပ်ကဲရေးဦးစီးဌာန(တိုင်းရုံး)၊ တောင်ငူခရိုင်နှင့် ပြည်ခရိုင်အစားအသောက်နှင့် ဆေးဝါးကွပ်ကဲရေးဦးစီးဌာနတို့၏ ၂၀၂၆ခုနှစ်၊ ဖေဖော်ဝါရီလအတွက် တိုးမြှင့်ဆောင်ရွက်မည့် ရှေ့လုပ်ငန်းစဉ် (Work Plan)များအား ပေးပို့အစီရင်ခံတင်ပြအပ်ပါသည်။'
])

<div class="collapse" id="{{ $id }}">
    <div class="p-3 border">
        <h6 class="text-dark">{{ $label }}</h6>
            <form action="{{ $route }}" method="POST" id="action-form-{{ $id }}">
            @csrf
            @method('POST')

            @if ($id == 'commentActionForm')
                <div class="mb-2">
                    <x-form.select id="{{ $id }}-input" name="officer_id" label="Officer" :options="$validOfficers"
                        class="lib-s2" />
                </div>
            @endif

            <div class="mb-2">
                <label for="description-form" class="mb-2">Comment :</label>
                <textarea class="form-control lib-summernote" name="description" id="description-form" rows="10">
                    {{ $status == 'approve' ? $defaultDescription : '' }}
                </textarea>
            </div>

            <div class="mb-2">
                <x-form.submit-btn :icon="false" formId="action-form-{{ $id }}" label="Submit" />
            </div>
        </form>
    </div>
</div>
