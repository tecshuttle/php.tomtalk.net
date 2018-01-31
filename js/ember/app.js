function getView(name) {
    var template = '';
    $.ajax({
        url: '/js/ember/' + name + '.html',
        async: false,
        success: function (text) {
            template = text;
        }
    });

    return Ember.Handlebars.compile(template);
};


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
        $.getJSON(url, function (data) {
            $.each(data, function (i, row) {
                item = type.create();
                item.setProperties(row);
                item.set('isLoaded', true);
                Ember.get(type, 'collection').pushObject(item);
            });
        });

        return Ember.get(type, 'collection');
    },
    updateRecord: function (url, type, model) {
        var collection = this;
        var data = JSON.parse(JSON.stringify(model));

        //为配合接口，整理一下数据
        data.sync_state = 'modify';
        data.devMode = true; //开发模式，不用判断cookie
        data.type_name = data.name;
        data._id = data.id;

        delete data.isLoaded;
        delete data.name;
        delete data.id;

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
    template: getView('application')
});

App.Router.map(function () {
    this.resource('index', { path: '/' });
    this.resource('list', { path: '/list' });

    this.resource('cat', { path: '/cat' }, function () {
        this.route("edit", {path: '/edit/:id'});
    });

    this.resource('tag', { path: '/tag' });

    this.resource('memo', { path: '/memo' }, function () {
        this.route("edit", {path: '/edit'});
    });

    this.resource('blog', { path: '/blog' });
    this.resource('book', { path: '/book' });
});

App.Todo = DS.Model.extend({
    title: DS.attr('string'),
    isCompleted: DS.attr('boolean')
});

App.Todo.FIXTURES = [
    {
        id: 1,
        title: 'Learn Ember.js',
        isCompleted: true
    },
    {
        id: 2,
        title: 'Make a Sample',
        isCompleted: false
    },
    {
        id: 3,
        title: 'Profit!',
        isCompleted: false
    }
];

App.IndexController = Ember.ArrayController.extend({
    actions: {
        createTodo: function (title) {
            if (!title.trim()) {
                return;
            }

            this.set('newTitle', '');

            var todo = this.store.createRecord('todo', {
                title: title,
                isCompleted: false
            });

            todo.save();
        },

        click: function (item) {
            item.set('isCompleted', item.get('isCompleted') ? false : true);

            //item.deleteRecord();

            //$.each(no, function (i, item) {
            //item.set('isCompleted', true);
            //item.deleteRecord();
            //});
        }
    }
});


App.ListController = Ember.Controller.extend({
    actions: {
        pClick: function (a, b, c) {
            console.log(this);
        },
        btnClick: function (a, b, c) {
            console.log(this);
        }
    }
});

App.MemoIndexController = Ember.Controller.extend({
    actions: {
        click: function (a, b, c) {
            console.log(this);
        }
    }
});


App.ApplicationAdapter = DS.FixtureAdapter.extend();


App.IndexRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('todo');
    }
});






