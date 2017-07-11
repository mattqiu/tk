<style>
    .rank_advancement{margin: 0 auto;
          background: -webkit-gradient(linear, left bottom, left top, color-stop(0, #afd9ee), color-stop(1, #d9edf7));
          border:none;
          border: 1px solid #85c5e5;
    }

    .rank_advancement ul li{ margin-top: 15px; list-style: none}
    .rank_advancement ul li b{ color: #3a87ad; font-size: 16px;}
    .rank_advancement ul li span{ color:#3a87ad;font-size: 14px;}
</style>
<div class="rank_advancement">
    <ul>
        <li>
            <b><?php echo lang('mso');?></b>
            <br>
            <span><?php echo lang('mso_context')?></span>
        </li>
        <li>
            <b><?php echo lang('sm');?></b>
            <br>
            <span><?php echo lang('sm_context')?></span>
        </li>
        <li>
            <b><?php echo lang('sd');?></b>
            <br>
            <span><?php echo lang('sd_context')?></span>
        </li>
        <li>
            <b><?php echo lang('vp');?></b>
            <br>
            <span><?php echo lang('vp_context')?></span>
        </li>
        <li>
            <b><?php echo lang('gvp');?></b>
            <br>
            <span><?php echo lang('gvp_context')?></span>
        </li>
        <li>
            <b><?php echo lang('finally explanation right');?></b>
            <br>
        </li>
    </ul>
</div>