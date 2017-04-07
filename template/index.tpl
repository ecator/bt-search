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
<script type="text/babel">
    var Result=React.createClass({
        getInitialState:function () {
            return {
                loading:true,
                results:[]
            };
        },
        componentDidMount:function () {
            if (this.props.keyword){
                var setState=this.setState.bind(this);
                $.post("/getresults","keyword="+encodeURI(this.props.keyword),function (data) {
//                        console.log(data);
                    setState({
                        loading:false,
                        results:data.results
                    });
                });
            }
        },
        componentDidUpdate:function () {
          //重新渲染完毕→突出关键词
            if (this.props.keyword && !this.state.loading){
                $(".title").each (function () {
                    $(this).html($(this).text().replace(/{$keyword}/g,"<span class=\"keyword\">{$keyword}</span>"));
                });
            }
        },
        render:function () {
            if (this.props.keyword){
                if (!this.state.loading){
                    //加载完成
                    if (this.state.results.length>0){
                        //成功加载出结果
                        return (
                            <table className="table">
                                <tr><th>#</th><th>名称</th><th>时间</th><th>下载</th></tr>
                                {
                                    this.state.results.map(
                                        (item,i)=> <tr><td>{
                                            i+1
                                        }</td><td className="title">{
                                            item.title
                                        }</td><td>{
                                            item.date
                                        }</td><td><a href={
                                            item.magnet
                                        }>磁力链接</a></td></tr>
                                    )
                                }
                            </table>
                        );
                    }else {
                        //没有找到结果
                        return <h1 className="text-center">没有找到{$keyword}的结果</h1>;
                    }
                }else {
                    //提示查找结果中
                    return <h1 className="text-center">搜索<span className="keyword">{$keyword}</span>中。。。</h1>;
                }
            }else {
                //没有进行搜索关键词
                return <p></p>;
            }
        }
    });
    ReactDOM.render(
            <Result keyword="{$keyword}" />,
        $('.result')[0]
    );
</script>
<script src="/js/index.js"></script>
</body>
</html>