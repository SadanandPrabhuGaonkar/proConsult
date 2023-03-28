Concrete.event.bind('contact_block.stack.stacks', function () {
    $(document).ready(function () {
        $('select[name="stack[]"]').select2_sortable();
    });
});