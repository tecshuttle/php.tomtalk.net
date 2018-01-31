$(function () {
    FastClick.attach(document.body);
});

Ember.Handlebars.helper('getDate', function (time) {
    var date = new Date(time * 1000);

    if (moment(date).format('D') == '1') {
        return moment(date).format('M月');
    } else {
        return moment(date).format('D');
    }
});

Ember.Handlebars.helper('getWeek', function (time) {
    var date = new Date(time * 1000);

    var e = moment(date).format('e');

    var week = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];

    return week[e];
});

Ember.Handlebars.helper('getTimeClass', function (time) {
    var date = new Date(time * 1000);

    var e = moment(date).format('e');

    if (e == 0 || e == 6) {
        return 'time-sunday';
    } else {
        return 'time-work';
    }
});

Ember.Handlebars.helper('toHtml', function (feat) {
    return feat ? feat.replace(/\n/g, "<br/>") : '';
});

App = Ember.Application.create({
    //LOG_TRANSITIONS: true,
    //LOG_TRANSITIONS_INTERNAL: true
});

//Model定义
App.Model = Ember.Object.extend();

App.Model.reopenClass({
    find: function (id, type) {
        var item = type.collection.findBy('id', id);
        return item;
    },
    findAll: function (url, type, key) {
        if (type.collection.length > 0) return;  //如果有数据，就不加载数据了。

        var collection = this;
        var item = null;

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function (res, status, xhr) {
                $.each(res, function (i, row) {
                    item = type.create();
                    item.setProperties(row);
                    item.set('isLoaded', true);
                    Ember.get(type, 'collection').pushObject(item);
                });

                collection.updateTitleDay();
            }
        });

        return Ember.get(type, 'collection');
    },
    findWeek: function (url, type, day, key) {
        Ember.get(type, 'collection').clear();

        var collection = this;
        var item = null;

        $.ajax({
            url: url,
            type: "GET",
            data: {day: day},
            dataType: "json",
            success: function (res, status, xhr) {
                $.each(res, function (i, row) {
                    item = type.create();
                    item.setProperties(row);
                    item.set('isLoaded', true);
                    Ember.get(type, 'collection').pushObject(item);
                });

                collection.updateTitleDay();
            }
        });

        return Ember.get(type, 'collection');
    },
    updateTitleDay: function () {
        var time = parseInt(App.DayModel.collection[0].time) * 1000;
        var date = new Date(time);
        $('#title_day').html(moment(date).format('YYYY-M'));
    },
    updateRecord: function (url, type, model) {
        var collection = this;
        var data = JSON.parse(JSON.stringify(model));

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            success: function (res, status, xhr) {
                if (res.id) {
                    model.set('isSaving', false);
                    model.setProperties(res);
                } else {
                    model.set('isError', true);
                }
            },
            error: function (xhr, status, err) {
                model.set('isError', true);
            }
        });
    }
});

App.ApplicationView = Ember.View.extend({
    templateName: 'application'
});


App.Router.map(function () {
    this.route("index", {path: "/"});
    this.resource('detail', { path: '/detail/:detail_id' }, function () {
    });
});

App.ApplicationAdapter = DS.RESTAdapter.extend({
    host: '/wife'
});

App.DayModel = App.Model.extend();

App.DayModel.reopenClass({
    collection: Ember.A(),
    find: function (id) {
        return App.Model.find(id, App.DayModel);
    },
    findAll: function () {
        return App.Model.findAll('/wife', App.DayModel, 'memo');
    },
    findWeek: function (day) {
        return App.Model.findWeek('/wife', App.DayModel, day, 'memo');
    },
    updateRecord: function (model) {
        App.Model.updateRecord('/todo/job_edit', App.DayModel, model);
    }
});

App.IndexRoute = Ember.Route.extend({
    model: function () {
        return App.DayModel.findAll();
    }
});

App.Day = DS.Model.extend({
    id: DS.attr(),
    time: DS.attr(),
    feat: DS.attr()
});

App.IndexView = Ember.View.extend({
    templateName: 'index',
    didInsertElement: function () {
        var view = this;
        var $view = this.$();

        var bounce_return = new Bounce();
        bounce_return.translate({
            from: { x: -($(window).width()), y: 0 },
            to: { x: 0, y: 0 },
            duration: 800,
            bounces: 0,
            stiffness: 5
        });

        bounce_return.applyTo($view).then(function () {
            bounce_return.remove();
        });
    },
    willDestroyElement: function () {
        //console.log('destroy');
    }
});

App.IndexController = Ember.ObjectController.extend({
    actions: {
        preWeek: function () {
            var time = (parseInt(App.DayModel.collection[0].time) - (3600 * 24)) * 1000;
            var date = new Date(time);
            App.DayModel.findWeek(moment(date).format('YYYY-M-D'));
        },

        nextWeek: function () {
            var time = (parseInt(App.DayModel.collection[6].time) + (3600 * 24)) * 1000;
            var date = new Date(time);
            App.DayModel.findWeek(moment(date).format('YYYY-M-D'));
        }
    }
});

App.ItemController = Ember.ObjectController.extend({
    actions: {
        click: function () {
            this.set('old', this.get('feat'));
            this.transitionTo('detail', this);
        }
    }
});

App.DetailView = Ember.View.extend({
    templateName: 'detail',
    didInsertElement: function () {
        $('textarea').autosize({append: ""});
        $('textarea').focus();

        var view = this;
        var $view = this.$();

        var bounce_forward = new Bounce();
        bounce_forward.translate({
            from: { x: ($(window).width()), y: 0 },
            to: { x: 0, y: 0 },
            duration: 800,
            bounces: 0,
            stiffness: 5
        });

        bounce_forward.applyTo($view).then(function () {
            bounce_forward.remove();
        });
    },
    willDestroyElement: function () {
        //console.log('destroy');
    }
});

App.DetailController = Ember.ObjectController.extend({
    actions: {
        cancel: function (item) {
            this.set('feat', this.get('old'));
            this.transitionTo('index');
        },

        save: function () {
            var me = this;

            $.ajax({
                url: "/wife/update",
                type: "POST",
                data: {
                    id: me.get('id'),
                    feat: me.get('feat')
                },
                dataType: "json",
                success: function (result) {
                    console.log(result);
                }
            });

            this.transitionTo('index');
        }
    }
});

//end file