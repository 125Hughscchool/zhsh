 
 <?php 
    require __DIR__ . '/../path.php';
    include __DIR__ . '/../app/controllers/teams.php";

    $teams = selectAll('team');

    include ("../include/head.php"); 

     //------sprite------>
    include ("../include/sprite.php"); 
    //------header------>
    include ("../include/pages_header.php"); 

?>
    <div class="rules" id="rules">
        <div class="container">
            
                <div class="advice_inner">
                    <h1 class="advice__title">Біздің мұғалімдеріміз</h1>
                    
                </div>
                
        
    
            <!-- <div class="docum__border docum__border--solid">
                <div class="docum__titles">2023-2028 жылдарға арналған мектепті дамыту бағдарламасы</div>
                <a class="btn btn--doc" target="_blank" href="https://docs.google.com/document/d/1iDPsleZBtDPTn2C0CQH2rl9Jbuy2I-F8/edit?usp=sharing&ouid=106521293178266206330&rtpof=true&sd=true">файлді жүктеу</a>
            </div>  -->
        </div>
    </div>

    <div class="container">
    <div class="team-grid">
            <div class="team__inner">
                <?php foreach ($teams as $team): ?>               
                        <div class="team__item">
                            <?php if($team['img']):?>
                                <div class="team__img">
                                    <img src="<?= BASE_URL . '/assets/img/team/' . $team['img']?>" alt="<?=mb_substr($team['name'], 0, 10, 'UTF-8') . '...'?>">
                                </div>
                            <?php endif;?>
                            <h3><?=$team['name'];?></h3>
                            <p><?=$team['profession'];?></p>
                        </div>
                <?php endforeach; ?>
            </div>
        </div>
                  
</div>
    
<?php
    //------resource------>
    include ("../include/resource.php");
    //------footer------>
    include ("../include/footer.php"); 
?>