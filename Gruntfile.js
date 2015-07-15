module.exports = function(grunt) {
  var excludeUpload = [
    '**/.DS_Store',
    '**/Thumbs.db',
    '**/*.ini',
    '**/src',
    'node_modules',
    'Gruntfile.js',
    '**/.ftppass*',
    '.grunt',
    '**/_*',
    'package.json',
    'npm-debug.log',
    '**/upload',
    '.sass-cache',
    '.git'/*,
    '*.scss',
    '*.map'*/
  ];

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        files: {
          'js/global.js': ['js/src/utils.js', 'js/src/menu.js'],
          'js/carousel.min.js': 'js/src/carousel.js'
        }
      }
    },
    ftpush: {
      build: {
        auth: {
          host: 'ftp.nielsriekert.nl',
          port: 21,
          authKey: 'live'
        },
        src: '',
        dest: '/domains/nielsriekert.nl/public_html/dev.astro',
        exclusions: excludeUpload,
        keep: '/images/upload',
        simple: false,
        useList: false
      },
      live: {
        auth: {
          host: 'ftp.nielsriekert.nl',
          port: 21,
          authKey: 'live'
        },
        src: '',
        dest: '/domains/nielsriekert.nl/public_html/astro',
        exclusions: excludeUpload,
        keep: '/images/upload',
        simple: false,
        useList: false
      }
    },
    imagemin: {
      dynamic: {
        files: [{
          expand: true,
          cwd: 'images/large_thumbs/src/',
          src: ['**/*.{png,jpg,gif,svg}'],
          dest: 'images/large_thumbs/'
        }]
      }
    },
    sass: {
      dist: {
        options: {
          style: 'compressed'
        },
        files: {
          'default.css': 'default.scss',
          'print.css': 'print.scss',
          'handheld_800.css': 'handheld_800.scss',
        }
      }
    },
    watch: {
      scripts: {
        files: ['**/*.php', '**/*.html'],
        tasks: ['ftpush:build'],
        options: {
          spawn: false
        }
      },
      style: {
        files: ['*.scss'],
        tasks: ['sass', 'ftpush:build'],
        options: {
          spawn: false
        }
      },
      javascript: {
        files: ['js/src/*.js'],
        tasks: ['uglify', 'ftpush:build'],
        options: {
          spawn: false
        }
      },
      images: {
        files: ['images/**/*.{png,jpg,gif,svg}', 'thumbs/**/*.{png,jpg,gif,svg}', 'pictures/**/*.{png,jpg,gif,svg}'],
        tasks: ['newer:imagemin', 'ftpush:build'],
        options: {
          spawn: false
        }
      }
    },
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.loadNpmTasks('grunt-ftpush');

  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.loadNpmTasks('grunt-contrib-sass');

  grunt.loadNpmTasks('grunt-newer');

  grunt.loadNpmTasks('grunt-contrib-imagemin');

  // Default task(s).
  grunt.registerTask('default', ['newer:uglify', 'sass', 'newer:imagemin', 'ftpush:build', 'watch']);
  grunt.registerTask('live', ['newer:uglify', 'sass', 'newer:imagemin', 'ftpush:live']);

};