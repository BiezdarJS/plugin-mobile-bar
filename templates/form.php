

    <?php


        $action_params = array('view' => 'form', 'action' => 'save');
        if($Slide->hasId()){
            $action_params['slideid'] = $Slide->getField('id');
        }

    ?>

    <form action="<?php echo $this->getAdminPageUrl($action_params); ?>" method="post" id="wp-fn-box-form">

    <?php
      wp_nonce_field($this->action_token);   
    ?>

        <table class="form-table">
        
            <tbody>
            
<!--                <tr class="form-field">
                    <th>
                        <label for="wp-fn-img">Zdjęcie ikony:</label>
                    </th>
                    <td>
                        <a class="button-secondary" id="select-img-btn">Wybierz ikonę z biblioteki mediów</a>
                        <input type="hidden" name="entry[img]" id="wp-fn-img" value="<?php /*echo $Slide->getField('img');*/ ?>">
                        <?php /*if($Slide->hasErrors('img')) :*/ ?>
                        <p class="description error"><?php /*echo $Slide->getError('img');*/ ?></p>                        
                        <?php /*else:*/ ?>
                        <p class="description">To pole jest wymagane</p>
                        <?php /*endif;*/ ?>                        
                        <p id="img-preview">
                            <?php /*if($Slide->getField('img') != NULL):*/ ?>
                            <img src="<?php /*echo $Slide->getField('img');*/ ?>" alt="">
                            <?php /*endif;*/ ?>                                 
                        </p>
                    </td>
                </tr>-->
                <tr class="form-field">
                    <th>
                        <label for="wp-fn-img">Zdjęcie ikony:</label>
                    </th>
                    <td>
                        <span>Wpisz jedno z następujących ikon:&nbsp;&nbsp; Flaticon-letter.svg, &nbsp;&nbsp;Flaticon-map-marker-point.svg, &nbsp;&nbsp; Flaticon-phone-receiver.svg, &nbsp;&nbsp; Flaticon-service-bell.svg </span>
                        <input type="text" name="entry[img]" id="wp-fn-img" value="<?php echo $Slide->getField('img'); ?>">
                        <?php if($Slide->hasErrors('img')) : ?>
                        <p class="description error"><?php echo $Slide->getError('img'); ?></p>                        
                        <?php else: ?>
                        <p class="description">To pole jest wymagane</p>
                        <?php endif ?> 
                    </td>
                </tr>                
                <tr class="form-field">
                    <th>
                        <label for="wp-fn-href">Link href do ikony:</label>
                    </th>
                    <td>
                        <input type="text" name="entry[href]" id="wp-fn-href" value="<?php echo $Slide->getField('href'); ?>">
                        <?php if($Slide->hasErrors('href')) : ?>
                        <p class="description error"><?php echo $Slide->getError('href'); ?></p>                        
                        <?php else: ?>
                        <p class="description">To pole jest wymagane</p>
                        <?php endif ?> 
                    </td>
                </tr>                 
                <tr class="form-field">
                    <th>
                        <label for="wp-fn-box-title">Tytuł do ikony:</label>
                    </th>
                    <td>
                        <input type="text" name="entry[title]" id="wp-fn-title" value="<?php echo $Slide->getField('title'); ?>">
                        <?php if($Slide->hasErrors('title')) : ?>
                        <p class="description error"><?php echo $Slide->getError('title'); ?></p>                        
                        <?php else: ?>
                        <p class="description">To pole jest wymagane</p>
                        <?php endif ?> 
                    </td>
                </tr>   
                
                <tr>
                    <th>
                        <label for="wp-fn-box-position">Pozycja:</label>
                    </th>
                    <td>
                        <input type="text" name="entry[position]" id="wp-fn-position" value="<?php echo $Slide->getField('position'); ?>">
                        <a class="button-secondary" id="get-last-pos">Pobierz ostatnią wolną pozycję</a>
                        <?php if($Slide->hasErrors('position')) : ?>
                        <p id="pos-info" class="description error"><?php echo $Slide->getError('position'); ?></p>                        
                        <?php else: ?>
                        <p id="pos-info" class="description">To pole jest wymagane</p>
                        <?php endif ?>                         
                    </td>
                </tr>       
                
                <tr>
                    <th>
                        <label for="wp-fn-box-published">Opublikowany:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="entry[published]" id="wp-fn-published" value="yes" <?php echo ($Slide->isPublished()) ? 'checked="checked"' : ''; ?>>
                    </td>
                </tr>                 
            
            </tbody>
        
        </table>
        
        
        <p class="submit">
            <a href="" class="button-secondary">Wstecz</a>
            &nbsp;
            <input type="submit" class="button-primary" value="Zapisz zmiany">
        </p>


    </form>