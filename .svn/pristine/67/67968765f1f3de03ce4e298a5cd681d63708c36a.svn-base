<html>
    <head>
        <meta charset="utf-8">
        <title>Debug</title>
        <script src="<?php echo base_url('js/new/jquery.min.js?v=2'); ?>"></script>
        <style>
            .time{
                color: grey;
                font-size: 0.9em;
            }
            .content_item{
                margin-bottom: 3px;
            }
        </style>
        <script>
            function clearAllLog() {
                $.ajax({
                    type: "POST",
                    url: "/common/clearAllLog",
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
    <p><button type="button" onclick="clearAllLog();">清空日志</button></p>
    <?php foreach ($logs as $log) { ?>
        <div class="content_item">
            <span class="time">--------------------------[<?php echo $log['create_time'] ?>]-----------------------------</span>
            <pre style="margin-top: 2px;margin-bottom: 45px;"><?php echo $log['content'] ?></pre>
        </div>
    <?php } ?>
</body>
</html>