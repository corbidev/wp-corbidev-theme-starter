document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('corbidev-admin-form');
    if (!form) return;

    function serializeForm(form) {
        const data = new FormData(form);
        return JSON.stringify(Object.fromEntries(data.entries()));
    }

    let initialState = serializeForm(form);
    let isDirty = false;

    form.addEventListener('input', function(){
        isDirty = serializeForm(form) !== initialState;
    });

    document.getElementById('corbidev-save').addEventListener('click', function(){
        const data = new FormData(form);

        fetch(CorbiDevAdmin.restUrl, {
            method: 'POST',
            headers: { 'X-WP-Nonce': CorbiDevAdmin.nonce },
            body: data
        }).then(function(){
            initialState = serializeForm(form);
            isDirty = false;
        });
    });

    document.getElementById('corbidev-cancel').addEventListener('click', function(){
        location.reload();
    });

    window.addEventListener('beforeunload', function(e){
        if (isDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

});
