<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <title><?php echo $title ?></title>
        <meta name="keywords" content="<?php echo $keywords ?>" />
        <meta name="description" content="<?php echo $description ?>">
        <meta name="author" content="tps-team">
        <link rel="stylesheet" href="/css/tps-xy.css">
        <script src="<?php echo base_url(THEME . '/js/jquery-1.11.1.js') ?>"></script>
        <script src="<?php echo base_url(THEME . '/js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo base_url('js/mobile_register.js?v=15'); ?>"></script>
        <script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
        <link rel="stylesheet" href="/css/h5_base.css?v=1">
    </head>
<?php $_usa=($curLocation_id=='840'||$curLocation_id=='000')?'_usa':'';?>
    <body>
        <div class="container f-b">
            <div class="row login">
                <!-- Nav tabs -->
                <ul class="nav t-c" role="tablist">
                    <li class="active"><a href="#reg" aria-controls="reg" role="tab" data-toggle="tab" onclick="selType(0,'<?php echo lang('regi_email') ?>','<?php echo lang('regi_register_now') ?>')"><?php echo lang('store_enroll') ?></a></li>
                    <li><a href="#login" aria-controls="profile" role="tab" data-toggle="tab" onclick="selType(1,'<?php echo lang('regi_email_usa') ?>','<?php echo lang('Finish') ?>')"><?php echo lang('is_account'.$_usa) ?></a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="reg">
                        <input type="hidden" name="reg_type" value="0">
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo lang('regi_parent_id'); ?></span>
                            <input type="text" class="form-control" value="<?php echo $memberDomainInfo ? $memberDomainInfo['id'] : "" ?>" name="parent_id" <?php echo $memberDomainInfo ? " disabled=disabled" : "" ?> placeholder="<?php echo lang('regi_parent_id'); ?>">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo lang('regi_email') ?></span>
                            <input name="email" id="email"  type="text" class="form-control" placeholder="<?php echo lang('regi_email') ?>">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo lang('regi_pwd') ?></span>
                            <input type="password" id="pwd" name="pwdOriginal" class="form-control" placeholder="<?php echo lang('regi_pwd') ?>">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo lang('captcha') ?></span>
                            <input type="text" name="captcha" class="form-control" placeholder="<?php echo lang('captcha'.$_usa) ?>">
                            <span class="input-group-addon"><input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha " value="<?php echo lang('get_captcha'.$_usa) ?>" ></span>
                        </div>
                        <p class="col-xs-12" style="min-height:20px;text-align: center;color: #808080;" id="tishi"></p>
                        <p class="col-xs-12"><input type="button" name="submit" style="letter-spacing:0px;" class="btn btn-primary" value="<?php echo lang('regi_register_now') ?>"></p>
                    </div>
                    <input type="hidden" value="<?php echo lang('resend_captcha') ?>" id="resend_captcha">
                    <input type="hidden" value="<?php echo lang('get_captcha') ?>" id="get_captcha">
                    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
                </div>
            </div>
        </div>
        <div class="container b-nav">
            <?php array_pop($sale_country); foreach ($sale_country as $country) { ?>
            <a data-id="<?php echo $country['country_id'] ?>" data-lang="<?php echo $country['default_language'] ?>" data-cur="<?php echo $country['default_flag'] ?>" style="<?php if ($curLocation_id == $country['country_id']){ echo 'color:#000000;';}else{echo 'color:#cccccc;';} ?>"
                   data-goods-sn="<?php echo isset($goods_sn) ? $goods_sn : '' ?>" data-jump="<?php echo isset($jump) ? TRUE : '' ?>" data-goods-sn-main="<?php echo isset($goods_sn_main) ? $goods_sn_main : '' ?>" class="change_location" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag'] ?>"></s><?php echo $country['name_' . $curLan] ?></a>
                <?php if ($country != end($sale_country)) { ?><span>&nbsp;|</span><?php } ?>
            <?php } ?>
        </div>
        <script>
            //切换区域
            $('.change_location').click(function () {
                var $t = $(this),
                        location_id = $t.attr('data-id'),
                        //currency_id = $t.attr('data-cur'),
                        //default_lang=$t.attr('data-lang'),
                        goods_sn = $t.attr('data-goods-sn'),
                        jump = $t.attr('data-jump'),
                        goods_sn_main = $t.attr('data-goods-sn-main');

                $.ajax({
                    type: "POST",
                    url: "/common/changeLanguage",
                    data: {location_id: location_id, jump: jump, goods_sn: goods_sn, goods_sn_main: goods_sn_main},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            });
        </script>
    </body>

</html>


