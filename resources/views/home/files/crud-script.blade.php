<script>
    currentSubject = {{ $currentSubject }};

    $('.files__list').crud({
        updateOptions: {
            url: '/files/{id}',
            type: 'PUT',
            element: '.crud-modify'
        },
        deleteOptions: {
            url: '/files/{id}',
            type: 'DELETE',
            element: '.crud-delete'
        },
        def: {
            'description': {
                'type': 'string'
            }
        }
    });
</script>