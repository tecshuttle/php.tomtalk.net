module.exports = function (grunt) {
    //'use strict';

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON( 'package.json' ),

        // Configuration to be run (and then tested).
        emberTemplates: {
            options: {
                //precompile: true,
                templateCompilerPath: '../ember/libs/ember-template-compiler.js',
                //handlebarsPath: '../ember/libs/handlebars-v2.0.0.js',
                templateBasePath: /templates\//
                //templateNamespace: 'HTMLBars'
            },
            'default': {
                files: {
                    'build/templates.js': ['templates/**/*.hbs']
                }
            }

        },
        uglify: {
            compress: {
                files: {
                    'build/compress.js': ['noqueue.js']
                },
                options: {
                    mangle: true,
                    compress: true
                }
            }
        },
        watch: {
            emberTemplates: {
                files: 'templates/**/*.hbs',
                tasks: ['emberTemplates']
            }
        }
    });

    // Actually load this plugin's task(s).
    //grunt.loadTasks('tasks');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-ember-templates');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // By default, lint and run all tests.
    //grunt.registerTask('default', ['emberTemplates', 'watch']);
    grunt.registerTask('default', ['emberTemplates','uglify']);
};
