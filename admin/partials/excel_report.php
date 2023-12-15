<script>
function getReport(id) {
        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: "get_concert_order_report",
                variation_id: id
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(response);
                a.href = url;
                a.download = id+'_order_report.csv';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        });
    }

</script>
