<!doctype html>
<html lang="en">
<head>
    {include "head.tpl"}

    <link rel="stylesheet" href="/css/index.css">
</head>
<body>
<div class="container">
    <div class="row search">
        <div class="col-xs-12 col-sm-6 col-sm-offset-2">
            <input value="{$keyword}" type="text" id="keyword" class="form-control" placeholder="请输入关键词">
        </div>
        <div class="col-xs-12 col-sm-2">
            <button id="search" class="btn btn-default btn-block">搜索</button>
        </div>
    </div>
    <div class="result table-responsive">

    </div>

</div>
<!--引入result组件-->
<script type="text/babel" data-keyword="{$keyword}" src="/js/result.jsx"></script>
</script>
<script src="/js/index.js"></script>
</body>
</html>