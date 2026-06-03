<?php
    
    require __DIR__ . '/../path.php';
include __DIR__ . '/../app/controllers/s_topics.php';

   
	
	$v_categoryId = isset($_GET['id']) ? $_GET['id'] : null;

	$categoryId = $v_categoryId; 
	$page = isset($_GET['page']) ? $_GET['page']: 1;
	$limit = 8;
    $offset = $limit * ($page - 1);
    $total_pages = round(countRowVac('s_posts') / $limit, 0);
	$posts = selectAllFromPostsWithUsersOnIndexVacance('s_posts', 'users', 's_topics', $categoryId, $limit, $offset );
   
    $v_category = selectOne('s_topics', ['id' => $v_categoryId]);


//  md($_GET['id']);
//     exit();



    include ("include/head.php"); 

     //------sprite------>
    include ("include/sprite.php"); 
    //------header------>
    include ("include/pages_header.php"); 

?>
    <div class="rules" id="rules">
        <div class="container">
            
                <div class="advice_inner">
                    <h1 class="advice__title"> <?=$v_category['name']; ?></h1>
                    <h2>Конкурс:ҚР Білім және ғылым министрінің «Мемлеткеттік білім беру ұйымдарының бірінші басшылары мен педагогтерін лауазымға тағайындау, 
                    лауазымынан босату қағидалары» 2012 жылғы 21 ақпандағы №57 бұйрығына (22.12.2022ж №513 өзгерістерімен) сәйкес өткізіледі
                    </h2>
					
                </div>

        </div>
    </div>

    
        <div class="container">
		<?php foreach ($posts as $post): ?>
                <div class="rules__inner">
					
                    <div class="rules__con">
                        <div class="rules__kv">
                            <div class="rulestime__kv">
								<?php
									$timestamp = strtotime($post['created_data']); 
									$day = date('d', $timestamp);
									$monthYear = date('m' . '.' . 'Y', $timestamp);
								?>
                                <div class="rulestime__t"><?php echo $day; ?></div>
                                <div class="rulestime__date"><?php echo $monthYear; ?></div>
                            </div>
							<a class="btn  btn--click" href="<?=BASE_URL . 'page_vacance.php?vacance=' . $post['id'];?>">толығырақ</a>
                        </div>
                        <div class="rules__content">
							<?php if(mb_strlen($post['title'],'UTF-8')>80):?>
								<a class="advice__title--vac" href="<?=BASE_URL . 'page_vacance.php?vacance=' . $post['id'];?>"><?=mb_substr($post['title'], 0, 80, 'UTF-8') . '...'?></a>                         
							<?php else:?>
								<a class="advice__title--vac" href="<?=BASE_URL . 'page_vacance.php?vacance=' . $post['id'];?>"><?=$post['title'];?></a>
							<?php endif;?>

                            <?php if(mb_strlen($post['content'],'UTF-8')>300):?>											
								<p class="text--rules"><?=mb_substr($post['content'], 0, 300, 'UTF-8') . '...'?></p>
							<?php else:?>
								<p class="text--rules"><?=$post['content'];?></p>
							<?php endif;?>

							<!-- <div class="docum__border docum__border--solid">
                            	<div class="docum__titles">Толық ақпарат алу үшін "ЖҮКТЕУ" сілтемесін басыңыз.</div>
                            	<a class="btn btn--doc" target="_blank" href="https://docs.google.com/document/d/1LLjw5xNQYpOg7e6qZU8JJMR1_euUGBuG/edit?usp=sharing&ouid=106521293178266206330&rtpof=true&sd=true">Файлді жүктеу</a>
							</div> 
							<div class="docum__border docum__border--solid">
								<div class="docum__titles">Нажмите на ссылку "СКАЧАТЬ", чтобы узнать подробности.</div>
								<a class="btn btn--doc" target="_blank" href="https://docs.google.com/document/d/1TSnQFq-kr9NVUkulXFpA9b_t8iaGi9no/edit?usp=sharing&ouid=106521293178266206330&rtpof=true&sd=true">Скачать файл</a>
							</div>  -->
                            
                        </div>
                    </div>
					
				</div>
				<?php endforeach; ?>
        </div>
    
    
<?php
    //------resource------>
    include ("include/resource.php");
    //------footer------>
    include ("include/footer.php"); 
?>