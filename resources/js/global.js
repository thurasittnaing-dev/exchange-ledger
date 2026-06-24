$(document).ready(function() {

    $(document).on("keyup", ".num-only", function () {
        var value = $(this).val();
        var cleanedValue = value.replace(/\D/g, "");
        $(this).val(cleanedValue);
    });

    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "inherit" );
    });

    $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "auto" );
    });



    // SELECT2 Normal
    $(".lib-s2").each(function () {
        if (!$(this).hasClass("select2-hidden-accessible")) {
            $(this).select2({
                allowClear: false,
                width: "100%",
            });
        }
    });


    const initModalSelect2 = (scope = document) => {
        $(scope).find(".lib-modal-s2").addBack(".lib-modal-s2").each(function () {
            var $this = $(this);
            if ($this.closest(".activity-expense-item-template").length) {
                return;
            }
            if (!$this.hasClass("select2-hidden-accessible")) {
                const $dropdownParent = $this.data("modal")
                    ? $(`#${$this.data("modal")}`)
                    : $this.closest(".modal");

                $this.select2({
                    allowClear: false,
                    width: "100%",
                    dropdownParent: $dropdownParent.length ? $dropdownParent : $(document.body),
                });
            }
        });
    };

    initModalSelect2();
    window.initModalSelect2 = initModalSelect2;

    const getSubstanceTree = () => window.substanceTree || [];

    const getSubstanceChildren = (parentId) => {
        const tree = getSubstanceTree();
        const normalizedParentId =
            parentId === null || parentId === undefined || parentId === ""
                ? null
                : Number(parentId);

        return tree.filter((substance) => {
            const substanceParentId =
                substance.parent_id === null || substance.parent_id === undefined
                    ? null
                    : Number(substance.parent_id);

            return substanceParentId === normalizedParentId;
        });
    };

    const getSubstancePath = (substanceId) => {
        const tree = getSubstanceTree();
        const byId = Object.fromEntries(
            tree.map((substance) => [String(substance.id), substance])
        );
        const path = [];
        let current = byId[String(substanceId)];

        while (current) {
            path.unshift(String(current.id));
            current = current.parent_id ? byId[String(current.parent_id)] : null;
        }

        return path;
    };

    const destroySubstanceCascadeSelect2 = ($container) => {
        $container.find(".lib-modal-s2").each(function () {
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2("destroy");
            }
        });
    };

    const buildSubstanceLevelSelect = (options, selectedValue = "") => {
        let html = '<div class="substance-cascade-level mb-2">';
        html += '<select class="form-control lib-modal-s2 substance-cascade-level-select">';
        html += '<option value="">Choose</option>';

        options.forEach((option) => {
            const selected =
                String(option.id) === String(selectedValue) ? " selected" : "";
            html += `<option value="${option.id}"${selected}>${option.name}</option>`;
        });

        html += "</select></div>";
        return html;
    };

    const updateSubstanceCascadeValue = ($container) => {
        const $hidden = $container.find(".substance-cascade-value");
        const $levels = $container.find(".substance-cascade-level-select");
        let selectedValue = "";

        $levels.each(function () {
            const value = $(this).val();
            if (value) {
                selectedValue = value;
            }
        });

        $hidden.val(selectedValue);
    };

    const renderSubstanceCascade = ($container, selectedSubstanceId = null) => {
        destroySubstanceCascadeSelect2($container);

        const $levelsContainer = $container.find(".substance-cascade-levels");
        $levelsContainer.empty();

        const path = selectedSubstanceId ? getSubstancePath(selectedSubstanceId) : [];
        const roots = getSubstanceChildren(null);

        if (path.length === 0) {
            $levelsContainer.append(buildSubstanceLevelSelect(roots));
        } else {
            path.forEach((substanceId, index) => {
                const parentId = index === 0 ? null : path[index - 1];
                const options = getSubstanceChildren(parentId);
                $levelsContainer.append(
                    buildSubstanceLevelSelect(options, substanceId)
                );
            });

            const lastChildren = getSubstanceChildren(path[path.length - 1]);
            if (lastChildren.length > 0) {
                $levelsContainer.append(buildSubstanceLevelSelect(lastChildren));
            }
        }

        updateSubstanceCascadeValue($container);

        const $modal = $container.closest(".modal");
        initModalSelect2($modal.length ? $modal[0] : document);
    };

    const initSubstanceCascadeSelects = (scope = document) => {
        $(scope)
            .find(".substance-cascade-select")
            .addBack(".substance-cascade-select")
            .each(function () {
                const $container = $(this);
                const selected =
                    $container.attr("data-selected") ||
                    $container.find(".substance-cascade-value").val() ||
                    null;

                renderSubstanceCascade($container, selected || null);
            });
    };

    const getActivityGroupTree = () => window.activityGroupTree || [];

    const getActivityGroupChildren = (rootId, parentId) => {
        const tree = getActivityGroupTree();
        const effectiveParentId =
            parentId === null || parentId === undefined || parentId === ""
                ? Number(rootId)
                : Number(parentId);

        return tree.filter(
            (activityGroup) =>
                Number(activityGroup.parent_id) === effectiveParentId
        );
    };

    const getActivityGroupPath = (selectedId, rootId) => {
        const tree = getActivityGroupTree();
        const byId = Object.fromEntries(
            tree.map((activityGroup) => [String(activityGroup.id), activityGroup])
        );
        const path = [];
        let current = byId[String(selectedId)];

        while (current && Number(current.id) !== Number(rootId)) {
            path.unshift(String(current.id));
            current = current.parent_id ? byId[String(current.parent_id)] : null;
        }

        return path;
    };

    const destroyActivityGroupCascadeSelect2 = ($container) => {
        $container.find(".lib-modal-s2").each(function () {
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2("destroy");
            }
        });
    };

    const buildActivityGroupLevelSelect = (options, selectedValue = "") => {
        let html = '<div class="activity-group-cascade-level mb-2">';
        html +=
            '<select class="form-control lib-modal-s2 activity-group-cascade-level-select">';
        html += '<option value="">Choose sub category</option>';

        options.forEach((option) => {
            const selected =
                String(option.id) === String(selectedValue) ? " selected" : "";
            html += `<option value="${option.id}"${selected}>${option.name}</option>`;
        });

        html += "</select></div>";
        return html;
    };

    const updateActivityGroupCascadeValue = ($container) => {
        const $hidden = $container.find(".activity-group-cascade-value");
        const $levels = $container.find(".activity-group-cascade-level-select");
        let selectedValue = "";

        $levels.each(function () {
            const value = $(this).val();
            if (value) {
                selectedValue = value;
            }
        });

        $hidden.val(selectedValue);
    };

    const renderActivityGroupCascade = (
        $container,
        selectedSubActivityGroupId = null
    ) => {
        destroyActivityGroupCascadeSelect2($container);

        const rootId = $container.attr("data-root-id");
        const $levelsContainer = $container.find(".activity-group-cascade-levels");
        $levelsContainer.empty();

        const path = selectedSubActivityGroupId
            ? getActivityGroupPath(selectedSubActivityGroupId, rootId)
            : [];
        const roots = getActivityGroupChildren(rootId, null);

        if (path.length === 0) {
            $levelsContainer.append(buildActivityGroupLevelSelect(roots));
        } else {
            path.forEach((activityGroupId, index) => {
                const parentId = index === 0 ? null : path[index - 1];
                const options = getActivityGroupChildren(rootId, parentId);
                $levelsContainer.append(
                    buildActivityGroupLevelSelect(options, activityGroupId)
                );
            });

            const lastChildren = getActivityGroupChildren(
                rootId,
                path[path.length - 1]
            );
            if (lastChildren.length > 0) {
                $levelsContainer.append(buildActivityGroupLevelSelect(lastChildren));
            }
        }

        updateActivityGroupCascadeValue($container);

        const $modal = $container.closest(".modal");
        initModalSelect2($modal.length ? $modal[0] : document);
    };

    const initActivityGroupCascadeSelects = (scope = document) => {
        $(scope)
            .find(".activity-group-cascade-select")
            .addBack(".activity-group-cascade-select")
            .each(function () {
                const $container = $(this);
                const selected =
                    $container.attr("data-selected") ||
                    $container.find(".activity-group-cascade-value").val() ||
                    null;

                renderActivityGroupCascade($container, selected || null);
            });
    };

    $(document).on(
        "change select2:select",
        ".substance-cascade-level-select",
        function () {
            const $container = $(this).closest(".substance-cascade-select");
            const $levelsContainer = $container.find(".substance-cascade-levels");
            const $currentLevel = $(this).closest(".substance-cascade-level");
            const selectedValue = $(this).val();

            $currentLevel.nextAll(".substance-cascade-level").each(function () {
                destroySubstanceCascadeSelect2($(this));
                $(this).remove();
            });

            if (!selectedValue) {
                updateSubstanceCascadeValue($container);
                return;
            }

            const children = getSubstanceChildren(selectedValue);
            if (children.length > 0) {
                $levelsContainer.append(buildSubstanceLevelSelect(children));

                const $modal = $container.closest(".modal");
                initModalSelect2($modal.length ? $modal[0] : document);
            }

            updateSubstanceCascadeValue($container);
        }
    );

    $(document).on(
        "change select2:select",
        ".activity-group-cascade-level-select",
        function () {
            const $container = $(this).closest(".activity-group-cascade-select");
            const rootId = $container.attr("data-root-id");
            const $levelsContainer = $container.find(".activity-group-cascade-levels");
            const $currentLevel = $(this).closest(".activity-group-cascade-level");
            const selectedValue = $(this).val();

            $currentLevel.nextAll(".activity-group-cascade-level").each(function () {
                destroyActivityGroupCascadeSelect2($(this));
                $(this).remove();
            });

            if (!selectedValue) {
                updateActivityGroupCascadeValue($container);
                return;
            }

            const children = getActivityGroupChildren(rootId, selectedValue);
            if (children.length > 0) {
                $levelsContainer.append(buildActivityGroupLevelSelect(children));

                const $modal = $container.closest(".modal");
                initModalSelect2($modal.length ? $modal[0] : document);
            }

            updateActivityGroupCascadeValue($container);
        }
    );

    $(document).on("shown.bs.modal", ".modal", function () {
        initModalSelect2(this);
        initSubstanceCascadeSelects(this);
        initActivityGroupCascadeSelects(this);
    });

    $(document).on(
        "submit",
        "form[id^='activity-group-expense-form-'], form[id^='ag-expense-update-form-']",
        function () {
            $(this).find(".substance-cascade-select").each(function () {
                updateSubstanceCascadeValue($(this));
            });
            $(this).find(".activity-group-cascade-select").each(function () {
                updateActivityGroupCascadeValue($(this));
            });
        }
    );

    const reindexActivityExpenseItems = ($list) => {
        $list.find(".activity-expense-item-row").each(function (itemIndex) {
            $(this).find("[name^='expenses'], [id^='expenses']").each(function () {
                ["name", "id"].forEach((attr) => {
                    const value = $(this).attr(attr);
                    if (!value) {
                        return;
                    }

                    $(this).attr(
                        attr,
                        value.replace(/expenses\[[^\]]+\]/, `expenses[${itemIndex}]`)
                    );
                });
            });
        });
    };

    const updateActivityExpenseRemoveButtons = ($list) => {
        const $rows = $list.find(".activity-expense-item-row");
        const canRemove = $rows.length > 1;

        $rows.each(function () {
            const $row = $(this);
            const $removeBtn = $row.find(".activity-expense-remove-item");

            if (!canRemove) {
                $removeBtn.closest(".col-md-1").remove();
                $row.find(".col-md-5").removeClass("col-md-5").addClass("col-md-6");
                return;
            }

            if (!$removeBtn.length) {
                $row.find(".col-md-6").last().removeClass("col-md-6").addClass("col-md-5");
                $row.append(`
                    <div class="col-md-1 mb-3 text-end">
                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger activity-expense-remove-item"
                            title="Remove item" aria-label="Remove item">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                `);
            }
        });
    };

    const destroyActivityExpenseSelect2 = ($row) => {
        $row.find(".lib-modal-s2").each(function () {
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2("destroy");
            }
        });
    };

    $(document).on("click", ".activity-expense-add-item", function () {
        const $section = $(this).closest(".activity-expense-items-section");
        const $list = $section.find(".activity-expense-items-list");
        const $template = $section.find("template.activity-expense-item-template");
        const templateHtml = $template[0]?.innerHTML;

        if (!templateHtml) {
            return;
        }

        const itemIndex = $list.find(".activity-expense-item-row").length;

        $list.append(
            templateHtml.replace(/expenses\[__INDEX__\]/g, `expenses[${itemIndex}]`)
        );

        reindexActivityExpenseItems($list);
        updateActivityExpenseRemoveButtons($list);
        initModalSelect2($section.closest(".modal")[0]);
    });

    $(document).on("click", ".activity-expense-remove-item", function () {
        const $list = $(this).closest(".activity-expense-items-list");

        if ($list.find(".activity-expense-item-row").length <= 1) {
            return;
        }

        destroyActivityExpenseSelect2($(this).closest(".activity-expense-item-row"));
        $(this).closest(".activity-expense-item-row").remove();
        reindexActivityExpenseItems($list);
        updateActivityExpenseRemoveButtons($list);
    });

    // summernote
    $('.lib-summernote').each(function () {
        $(this).summernote({
            placeholder: 'Type your content here...',
            tabsize: 2,
            height: 300,
            minHeight: null,
            maxHeight: null,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    // FLATPICKR
    flatpickr(".lib-flatpickr", {
        dateFormat: "d-m-Y",
    });

    // FLATPICKR Month Only
    flatpickr(".lib-flatpickr-month", {
        plugins: [
            window.monthSelectPlugin({
                shorthand: true,
                dateFormat: "m-Y",
                altFormat: "F Y",
                theme: "light"
            })
        ]
    });


    // FLATPICKR RANGE
    flatpickr(".lib-flatpickr-range", {
        mode: "range",
        dateFormat: "d-m-Y",
    });

    // Impersonate
    $(document).on('change','#master_select',function(e){
        $('#impersonate_form').submit();
    });

    // Delete Btn
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();

        let url = $(this).data('url');
        let form = $('#global-delete-form');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.attr('action', url);
                form.submit();
            }
        });
    });

    // Confirm Btn
    $(document).on('click', '.btn-confirm', function(e) {
        e.preventDefault();

        let url = $(this).data('url');
        let form = $('#global-update-form');

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to do this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#31D492',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, do it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.attr('action', url);
                form.submit();
            }
        });
    });
});

window.actionFormSubmit = (button, formId) => {
    $(button).attr('disabled', 'disabled');
    $(button).addClass('disabled');
    $(button).text('Processing...');

    $(formId).submit();
};
