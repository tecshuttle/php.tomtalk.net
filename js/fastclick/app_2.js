$(function () {
    //FastClick.attach(document.body);
});

App = Ember.Application.create({
    //LOG_TRANSITIONS: true,
    //LOG_TRANSITIONS_INTERNAL: true
});

App.ApplicationAdapter = DS.FixtureAdapter;
//App.ApplicationAdapter = DS.LSAdapter; //DS.FixtureAdapter;

App.ApplicationView = Ember.View.extend({
    templateName: 'application'
});

App.IndexView = Ember.View.extend({
    templateName: 'index'
});

App.DetailView = Ember.View.extend({
    templateName: 'detail',
    didInsertElement: function () {
        console.log('insert');
        var view = this;
        var $view = this.$();//.find('.pane');
        $view.hide();
        $view.slideDown(1000);
    },
    willDestroyElement: function () {
        console.log('destroy');
        var view = this;
        var $view = this.$();//.find('.pane');
        $view.show();
    }
});

App.Router.map(function () {
    this.resource('index', {path: '/'}, function () {
        //this.route('create');
    });

    this.resource('create', {path: '/create'}, function () {
        //this.route('create');
    });

    this.resource('detail', { path: '/detail/:detail_id' }, function () {
    });

    this.resource('edit', { path: '/edit/:edit_id' }, function () {
        //this.route('edit', {path: '/detail/edit'});
    });
});

App.IndexController = Ember.ArrayController.extend({
    actions: {
        click: function (dish) {
            dish.set('click', true);
        }
    },
    dishCount: function () {
        return this.get('model.length');
    }.property('@each')
});


App.DetailController = Ember.ObjectController.extend({
    actions: {
        click: function () {
            this.transitionTo('index');
        }
    }
});

App.EditController = Ember.ObjectController.extend({
    actions: {
        save: function () {
            var dish = this.get('model');
            dish.save(); // this will tell Ember-Data to save/persist the new record
            this.transitionTo('detail', dish); // then transition to the current user
        },
        delete: function () {
            this.get('model').deleteRecord();
            this.get('model').save();
            this.transitionTo('index'); // then transition to the current user
        }
    }
});

App.CreateController = Ember.ObjectController.extend({
    actions: {
        save: function () {
            // just before saving, we set the creationDate
            //this.get('model').set('creationDate', new Date());

            // create a record and save it to the store
            var dish = this.store.createRecord('dish', this.get('model'));
            dish.set('photo', '/images/noodle.jpg');
            dish.save();

            // redirects to the user itself
            this.transitionToRoute('detail', dish);
        }
    }
});

App.Dish = DS.Model.extend({
    name: DS.attr(),
    price: DS.attr(),
    click: DS.attr(),
    photo: DS.attr(),
    creationDate: DS.attr()
});

App.Dish.FIXTURES = [
    { id: 1, click: false},
    { id: 2, click: false },
    { id: 3, click: false },
    { id: 4, click: false },
    { id: 5, click: false },
    { id: 6, click: false },
    { id: 7, click: false },
    { id: 8, click: false },
    { id: 9, click: false }
];

App.IndexRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('dish');
    },
    redirect: function () {
        //this.transitionTo('index');
    }
});

App.CreateRoute = Ember.Route.extend({
    model: function () {
        // the model for this route is a new empty Ember.Object
        return Em.Object.create({});
    },

    // in this case (the create route), we can reuse the user/edit template
    // associated with the usersCreateController
    renderTemplate: function () {
        this.render('edit', {
            controller: 'create'
        });
    }
});

App.DetailRoute = Ember.Route.extend({
    model: function (params) {
        //console.log(params);
        return this.store.find('dish', params.detail_id);
    }
});

App.EditRoute = Ember.Route.extend({
    model: function (params) {
        //console.log(params);
        return this.store.find('dish', params.edit_id);
    }
});

App.MyAwesomeComponent = Em.Component.extend({
    didInsertElement: function () {
        console.log('did');
        this.$().on('click', '.child .elem', function () {
            // do stuff with jQuery
        });
    },
    willDestroyElement: function () {
        console.log('did');
        this.$().off('click');
    }
});