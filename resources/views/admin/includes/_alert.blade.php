@if (session('success'))
    <script type="module">
        notify.success({
            message: "{{ session('success') }}",
            dismissible: true,
            position: {
                x: 'right',
                y: 'top',
            },
            duration: 5000,
        });
    </script>
@endif

@if (session('error'))
    <script type="module">
        notify.error({
            message: "{{ session('error') }}",
            dismissible: true,
            position: {
                x: 'right',
                y: 'top',
            },
            duration: 5000,
        });
    </script>
@endif
