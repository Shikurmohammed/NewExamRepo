function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var $state = $(
        '<span><input type="checkbox" class="checkbox" /> ' + state.text + '</span>'
    );
    return $state;
}

$('.select2').select2({
    templateResult: formatState,
    templateSelection: formatState,
    closeOnSelect: false
});