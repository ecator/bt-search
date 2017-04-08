/**
 * Created by qule on 2017/4/8.
 */
var Result=React.createClass({
    getInitialState:function () {
        return {
            loading:true,
            results:[]
        };
    },
    componentDidMount:function () {
        //首次渲染完毕
        if (this.props.keyword){
            //post请求数据
            var setState=this.setState.bind(this);
            $.post("/getresults","keyword="+encodeURI(this.props.keyword),function (data) {
                       // console.log(typeof data);
                if (typeof(data)=="object") {
                    //请求成功
                    setState({
                        loading: false,
                        results: data.results
                    });
                }else {
                    //请求失败
                    setState({
                        loading:false
                    });
                }
            });
        }

        //显示计时
        if (this.props.keyword && this.state.loading) {
            //还在渲染中，显示计时
            var seconds = 0;
            var timer = function () {
                if (this.state.loading) {
                    // console.log(seconds);
                    $("#timer").text(String((seconds++)/10) + "秒");
                    setTimeout(timer, 100);
                }
            }.bind(this);
            timer();
        }

    },
    componentDidUpdate:function () {
        //重新渲染完毕
        var that=this;
        if (this.props.keyword && !this.state.loading){
            //已经渲染出结果，突出显示关键词
            var keywords=that.props.keyword.split(" ");
            $(".title").each (function () {
                var title=$(this).text();
                for (var i=0;i<keywords.length;i++) {
                    var pattern = new RegExp(keywords[i], "g");
                    title=title.replace(pattern, "<span class=\"keyword\">" + keywords[i] + "</span>");
                }
                $(this).html(title);
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
                        <table className="table table-condensed table-hover table-striped">
                            <tr><th className="index">#</th><th className="name">名称</th><th className="date">时间</th><th className="download">下载</th></tr>
                            {
                                this.state.results.map(
                                    (item,i)=> <tr><td>{i+1}</td><td className="title">{item.title}</td><td>{item.date}</td><td><a href={item.magnet}>磁力链接</a></td></tr>
                                )
                            }
                        </table>
                    );
                }else {
                    //没有找到结果
                    return <h3 className="text-center">没有找到{this.props.keyword}的结果</h3>;
                }
            }else {
                //提示查找结果中
                return <h3 className="text-center">搜索<span className="keyword">{this.props.keyword}</span>中<br/><span id="timer"></span></h3>;
            }
        }else {
            //没有进行搜索关键词
            return <h3 className="text-center">欢迎访问bt搜～</h3>;
        }
    }
});
// console.log($("script"));
var keyword=$("script[type='text/babel']:last").data("keyword");
ReactDOM.render(
    <Result keyword={keyword} />,
    $('.result')[0]
);