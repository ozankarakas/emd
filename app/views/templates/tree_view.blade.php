<div class="dd" id="nestable" style="margin: auto; float: none;"> 
    <ol class="dd-list">
        <?php 
        foreach ($tree as $key1 => $operator)
        {
            ?>
            <li class="dd-item dd-item dd-collapsed" data-id="">
                <div class="dd-handle dd-nodrag">
                    <?php echo $key1;?> 
                </div>
                <ol class="dd-list">
                    <?php 
                    foreach ($operator as $key2 => $region)
                    {
                        ?>
                        <li class="dd-item region-item" data-id="">
                            <div class="dd-handle dd-nodrag bg-color-greenLight txt-color-white">
                                <?php echo $key2;?>
                            </div>
                            <ol class="dd-list">
                                <?php
                                foreach ($region as $key3 => $contract)
                                {
                                    ?>
                                    <li class="dd-item contract-item" data-id="">
                                        <div class="dd-handle dd-nodrag bg-color-pink txt-color-white">
                                            <?php echo $key3;?>
                                        </div>
                                        <ol class="dd-list">
                                            <?php 
                                            foreach ($contract as $key4 => $leisureCentre)
                                            {
                                                ?>
                                                <li class="dd-item leisureCentre-item" data-id="">
                                                    <div class="dd-handle dd-nodrag bg-color-blue txt-color-white">
                                                        <?php echo $leisureCentre;?>
                                                        <div class="pull-right">
                                                            <a href="<?php echo $key4->id; ?>" class="label label-warning">&nbsp;<i class="fa fa-eye edit-tree"></i> Details &nbsp;</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php 
                                            } 
                                            ?>
                                        </ol>
                                    </li>
                                    <?php 
                                } 
                                ?>
                            </ol>
                        </li>
                        <?php 
                    } 
                    ?>
                </ol>
            </li>
            <?php 
        } 
        ?>
    </ol>
</div>