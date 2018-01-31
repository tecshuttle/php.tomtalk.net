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
            type: "POST",
            data: {day: '2015-04-16'},
            dataType: "json",
            success: function (res, status, xhr) {
                $.each(res, function (i, row) {
                    item = type.create();
                    item.setProperties(row);
                    item.set('isLoaded', true);
                    Ember.get(type, 'collection').pushObject(item);
                });
            }
        });

        return Ember.get(type, 'collection');
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

App.CatIndexView = Ember.View.extend({
    templateName: 'cat/index',
    didInsertElement: function () {
        var view = this;
        var $view = this.$();

        var byId = function (id) {
            return document.getElementById(id);
        };

        Sortable.create(byId('job-list'), {
            animation: 150,
            handle: '.drag-handle'
        });


        console.log('insert');
    },
    willDestroyElement: function () {
        console.log('destroy');
    }
});


App.Router.map(function () {
    this.resource('index', { path: '/' });
    this.resource('month', { path: '/month' });

    this.resource('cat', { path: '/cat' }, function () {
        //this.route("edit", {path: '/edit/:id'});
    });

    this.resource('edit', { path: '/edit/:edit_id' }, function () {
    });
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
        }
    }
});

App.ApplicationAdapter = DS.FixtureAdapter.extend();

App.IndexRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('todo');
    }
});







