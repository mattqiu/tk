<div class="search-well">
    <form action="<?php echo base_url('admin/admin_right');?>" class="form-inline" method="GET">
      <input name="right_name" type="text" class="form-control" style="">
      <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>

</div>

<div class="well">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th><?php echo 'id'; ?></th>
            <th><?php echo 'admin_id'; ?></th>
            <th><?php echo 'right_name'; ?></th>
            <th><?php echo 'right_key'; ?></th>
            <th><?php echo 'right'; ?></th>
            <th><?php echo 'remark'; ?></th>
            <th><?php echo 'time'; ?></th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['id'] ?></td>
                    <td><?php echo $item['admin_id'] ?></td>
                    <td><?php echo $item['right_name'] ?></td>
                    <td><?php echo $item['right_key'] ?></td>
                    <td>
                        <?php
                            $count = count(unserialize($item['right']));

                            if($count <=6){

                                echo  implode(',',unserialize($item['right']));

                            }else{
                                $i   = 0;
                                $a   = 1;
                                $str = '';
                                foreach(unserialize($item['right']) as $v){
                                    $str = $str.$v;
                                    if($count==$a)
                                    {
                                        $str = $str.'';
                                    }else{
                                        $str = $str.',';
                                    }
                                    $i++;
                                    $a++;
                                    if($i%6==0){
                                        $str.='<br>';
                                        $i=0;
                                    }
                                }
                                echo $str;
                            }
                        ?>
                    </td>
                    <td><?php echo $item['remark'] ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                    <td><a href="<?php echo base_url('admin/admin_right/editRight').'?id='.$item['id']; ?>"><?php echo 'Edit' ?></a></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;