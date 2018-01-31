App.CatModel = App.Model.extend();

App.CatModel.reopenClass({
    collection: Ember.A(),
    find: function (id) {
        return App.Model.find(id, App.CatModel);
    },
    findAll: function () {
        return App.Model.findAll('/todo/get_jobs_of_day', App.CatModel, 'memo');
    },
    updateRecord: function (model) {
        App.Model.updateRecord('/todo/job_edit', App.CatModel, model);
    }
});

App.CatRoute = Ember.Route.extend({
    model: function () {
        return App.CatModel.findAll();
    }
});

App.EditRoute = Ember.Route.extend({
    model: function (item) {
        console.log(item);
        return App.CatModel.find(item.id, App.CatModel);
    }
});

App.CatIndexController = Ember.ObjectController.extend({
    actions: {
        new: function () {
            var item = App.CatModel.create();
            item.set('color', '#000000');
            console.log(item);
        },

        click: function (item) {

            this.transitionToRoute('edit', item);

        }
    }
});

App.EditController = Ember.ObjectController.extend({
    actions: {
        save: function (item) {
            var validated = true;
            //Ommiting the validation from the code listing

            if (validated) {
                var model = this.get('content');
                App.CatModel.updateRecord(model);

                this.transitionToRoute('cat');
            }
        }
    }
});

