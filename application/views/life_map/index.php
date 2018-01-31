<!DOCTYPE html>
<html lang="en">
<head>
    <title>Life Map</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap-3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/life_map.css">
</head>

<body>
    <div id="lifeMap"></div>
</body>

<script src="/js/jquery-1.11.1.min.js"></script>
<script src="/css/bootstrap-3.1.1/js/bootstrap.min.js"></script>
<script src="/js/react/react.js"></script>
<script src="/js/react/react-dom.js"></script>

<script src="/js/react/ReactRouter.min.js"></script>
<script src="/js/react/browser.min.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/life_map/index.js"></script>

<script type="text/babel">
    const Router = ReactRouter.Router;
    const Route = ReactRouter.Route;
    const Link = ReactRouter.Link;

    var app = app || {};

	app.ALL_TODOS = 'all';
	app.ACTIVE_TODOS = 'active';
	app.COMPLETED_TODOS = 'completed';

    var LifeMap = React.createClass({
        render: function() {
            return (
                <div>
                    <div id="toolBar">
                        <Link to="/mil"> Month In Life </Link>
                        <Link to="/yil"> Year In Life </Link>
                    </div>
                    {this.props.children}
                </div>
            );
        }
    });

    var MonthInLife = React.createClass({
        getInitialState: function() {
            var month = [];

            for(var i=0; i<lifeSpanMonth; i++) {
                var birthDay = moment(new Date('1979-03-12'));

                month.push({
                    time: parseInt(birthDay.add(i, 'M').format('X'))
                });
            }

            return {
                user: null,           //用户数据，判断操作权限
                lifeMapMonth: month,
                viewType: 'life'      //life|year|month
            };
        },

        componentDidMount: function () {
			var setState = this.setState;
			win_resize();
		},

		handleChange: function (event) {
			this.setState({newTodo: event.target.value});
		},

        render: function() {
            var me = this;
            var life = '';

            if (this.state.viewType === 'life') {
                var culMonthFirstDay = parseInt(moment(moment().format('YYYY-MM')+'-01').format('X'));
                var passToday = false;

                var life = this.state.lifeMapMonth.map(function(m, i){
                    var clsName = 'month ';

                    if (m.time < culMonthFirstDay) {
                        clsName += 'past';
                    } else {
                        if (passToday) {
                            clsName += 'future';
                        } else {
                            clsName += 'present';
                            passToday = true;
                        }
                    }

                    var to = '/month/' + moment(new Date(m.time * 1000)).format('YYYY-MM');

                    return <Link to={to} key={i+1}>
                        <div className={clsName} key={i+1} onClick={me._viewMonth.bind(null, i)}> &nbsp; </div>
                    </Link>;
                });
            }

            return (<div id="month-life">{life}</div>);
        },

        _viewMonth: function(idx) {
            this.setState({
                viewType: 'month'
            });
        }
    });


    var YearInLife = React.createClass({
        getInitialState: function() {
            var month = [];

            for(var i=0; i<(lifeSpanMonth/12); i++) {
                var birthDay = moment(new Date('1979-03-12'));

                month.push({
                    time: parseInt(birthDay.add(i, 'Y').format('X'))
                });
            }

            return {
                user: null,           //用户数据，判断操作权限
                lifeMapMonth: month,
                viewType: 'life'      //life|year|month
            };
        },

        componentDidMount: function () {
			var setState = this.setState;
			win_resize();
		},

		handleChange: function (event) {
			this.setState({newTodo: event.target.value});
		},

        render: function() {
            var me = this;
            var life = '';

            if (this.state.viewType === 'life') {
                var culMonthFirstDay = parseInt(moment(moment().format('YYYY-MM')+'-01').format('X'));
                var passToday = false;

                var life = this.state.lifeMapMonth.map(function(m, i){
                    var clsName = 'year ';

                    if (m.time < culMonthFirstDay) {
                        clsName += 'past';
                    } else {
                        if (passToday) {
                            clsName += 'future';
                        } else {
                            clsName += 'present';
                            passToday = true;
                        }
                    }

                    var to = '/year/' + moment(new Date(m.time * 1000)).format('YYYY');

                    return <Link to={to} key={i+1}>
                        <div className={clsName} key={i+1} onClick={me._viewMonth.bind(null, i)}>
                            {moment(new Date(m.time * 1000)).format('YYYY')}
                        </div>
                    </Link>;
                });
            }

            return (<div id="year-life">{life}</div>);
        },

        _viewMonth: function(idx) {
            this.setState({
                viewType: 'month'
            });
        }
    });

    const Year = React.createClass({
        getInitialState: function() {
            return {
                yearStr: moment().format('YYYY')
            };
        },
        componentDidMount() {
            if (this.props.params.year !== undefined) {
                this.setState({
                    yearStr: this.props.params.year
                });
            }

            win_resize();
        },
        componentDidUpdate() {
            win_resize();
        },
        render() {
            var me = this;
            var months = [];
            var culMonth = moment(new Date(me.state.yearStr + '-01-01'));

            for(var i = 0; i < 12; i++) {
                months.push({
                    month: culMonth.format('YYYY-MM')
                });

                culMonth.add(1, 'M');
            }

            var clsName = 'monthInYear'

            var year = months.map(function(m, i){
                var to = '/month/' + m.month;

                return (
                    <Link to={to} key={i+1}>
                        <div className={clsName} onClick={me._viewMonth.bind(null, i)}> {m.month} </div>
                    </Link>
                );
            });

            return(<div id='year'>{year}</div>);
        },

        _viewMonth: function() {
            //none
        }
    })

    const Month = React.createClass({
        getInitialState: function() {
            return {
                monthStr: moment().format('YYYY-MM') + '-01'
            };
        },
        componentDidMount() {
            if (this.props.params.month !== undefined) {
                this.setState({
                    monthStr: this.props.params.month + '-01'
                });
            }

            win_resize();
        },
        componentDidUpdate() {
            win_resize();
        },
        render() {
            var me = this;
            var firstDay = moment(new Date(this.state.monthStr));

            var n_days = firstDay.daysInMonth();

            var days = [];

            for(var i=firstDay.format('E'); i>1; i--) {
                var beforeDay = moment(new Date(this.state.monthStr));
                beforeDay.subtract(i-1, 'days');

                days.push({
                    day: beforeDay.format('YYYY-MM-DD')
                });
            }

            for(var i = 0; i < n_days; i++) {

                days.push({
                    day: firstDay.format('YYYY-MM-DD')
                });

                firstDay.add(1, 'days');
            }

            var clsName = 'day'

            var month = days.map(function(m, i){
                return (
                    <div className={clsName} key={i+1} onClick={me._viewMonth.bind(null, i)}> {m.day} {moment(new Date(m.day)).format('dddd')} </div>
                );
            });

            return(<div id='month'>{month}</div>);
        },

        _viewMonth: function() {
            //none
        }
    })

    ReactDOM.render(
        <Router>
            <Route path="/" component={LifeMap}>
                <Route path="mil" component={MonthInLife} />
                <Route path="yil" component={YearInLife} />
                <Route path="year" component={Year} />
                <Route path="year/:year" component={Year} />
                <Route path="month" component={Month} />
                <Route path="month/:month" component={Month} />
            </Route>
        </Router>,
        document.getElementById('lifeMap')
    );
</script>

</html>