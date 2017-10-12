<div class="tree" style="font-size: 16px;">
    <ul>
        <?php 
        foreach ($tree as $key1 => $operator)
        {
            ?>
            <li>
                <span class="label label-success"><i class="fa fa-lg fa-plus-circle"></i> <?php echo $key1;?></span>
                <ul>
                    <?php 
                    foreach ($operator as $key2 => $region)
                    {
                        ?>
                        <li style="display:none">
                            <span class="label label-warning"><i class="fa fa-lg fa-plus-circle"></i> <?php echo $key2;?></span>
                            <ul>
                                <?php
                                foreach ($region as $key3 => $contract)
                                {
                                    ?>
                                    <li style="display:none">
                                        <span class="label label-danger"><i class="fa fa-lg fa-plus-circle"></i> <?php echo $key3;?></span>
                                        <ul>
                                           <?php 
                                           foreach ($contract as $key4 => $leisureCentre)
                                           {
                                            ?>
                                            <li style="display:none">
                                                <span class="label label-default"><?php echo $leisureCentre;?></span>
                                            </li>
                                            <?php 
                                        } 
                                        ?>
                                    </ul>
                                </li>
                                <?php 
                            } 
                            ?>
                        </ul>
                    </li>
                    <?php 
                } 
                ?>
            </ul>
        </li>
        <?php 
    } 
    ?>
</ul>
</div>