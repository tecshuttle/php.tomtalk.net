$(function () {
    FastClick.attach(document.body);
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
    templateName: 'detail'
});

App.Router.map(function () {
    this.resource('index', {path: '/'}, function () {
        //this.route('create');
    });

    this.resource('detail', { path: '/detail/:detail_id' }, function () {
    });

});

App.IndexController = Ember.ArrayController.extend({
    sortProperties: ['price'],
    sortAscending: false,
    actions: {
        click: function () {
            //this.transitionTo('detail');
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


App.Dish = DS.Model.extend({
    name: DS.attr(),
    price: DS.attr(),
    photo: DS.attr(),
    creationDate: DS.attr()
});

App.Dish.FIXTURES = [
    {
        id: 1,
        price: 7.00,
        name: '拌米粉',
        photo: '/images/noodle.jpg',
        creationDate: '2015-03-30'
    },
    {
        id: 2,
        price: 5.00,
        name: '豆浆',
        photo: '/images/beam_milk.jpg',
        creationDate: '2015-03-30'
    }
];

App.IndexRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('dish');
    }
});


App.DetailRoute = Ember.Route.extend({
    model: function (params) {
        //console.log(params);
        return this.store.find('dish', params.detail_id);
    }
});