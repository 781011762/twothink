<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"D:\tt.com\public/../application/home/view/default/index\ajax_page.html";i:1508072058;}*/ ?>

    <?php $__CATE__ = model('Category')->getChildrenId($category);$__WHERE__ = model('Document')->listMap($__CATE__);$__LIST__ = \think\Db::name('Document')->where($__WHERE__)->field($field)->order('`level` DESC,`create_time` DESC')->paginate(5);if($__LIST__){ $__LIST__=$__LIST__->toArray(); $__LIST__=$__LIST__['data'];} if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): $i = 0; $__LIST__ = $__LIST__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$article): $mod = ($i % 2 );++$i;?>
    <div class="row noticeList">
        <a href="<?php echo url('Article/detail?id='.$article['id']); ?>">
            <div class="col-xs-2">
                <img class="noticeImg" src="__ROOT__<?php echo get_cover_path($article['cover_id']); ?>"/>
            </div>
            <div class="col-xs-10">
                <a href="<?php echo url('Article/detail?id='.$article['id']); ?>">
                    <p class="title"><?php echo $article['title']; ?></p>
                    <p class="intro"><?php echo $article['description']; ?></p></a>
                <p class="info">浏览: <?php echo $article['view']; if($category == 44): ?><a href="<?php echo url('member/activeApplication?id='.$article['id']); ?>">申请参加</a><?php endif; ?>
                    <span class="pull-right"><?php echo date('Y-m-d H:i',$article['create_time']); ?></span>
                </p>
            </div>
        </a>
    </div>
<?php endforeach; endif; else: echo "" ;endif; ?>
<button class="more" onclick="loadPage(<?php echo $category; ?>,<?php echo $page; ?>)">获取更多</button>

