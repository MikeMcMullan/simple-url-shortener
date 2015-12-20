Vue.config.debug = true;

var app = new Vue({
    el: '#app',

    data: {
        isValid: false,
        customToken: '',
        url: '',
        password: '',
        shortenedUrl: '',

        loadingResults: false,
        displayingResults: false,

        validationErrors: {
            customToken: '',
            url: '',
            password: ''
        }
    },

    ready: function() {
        document.getElementsByTagName('body')[0].className = '';
    },

    watch: {
        url: function() {
            this.validateForm();
        },

        customToken: function() {
            this.validateForm();
        },

        password: function() {
            this.validateForm();
        }
    },

    methods: {
        resetForm: function() {
            this.url = '';
            this.customToken = '';
            this.password = '';
              
            Vue.nextTick(function() {
                for (var key in this.validationErrors) {
                  this.validationErrors[key] = '';
                }
            }.bind(this));
        },

        shortenUrl: function() {
            this.validateForm();

            if (! this.isValid) {
                return;
            }

            this.loadingResults = true;
          
            this.$http.get('/shorten', {
                url: this.url,
                custom: this.customToken,
                password: this.password
            }, function(data, status, request) {
                this.shortenedUrl = data.error || data.shorturl;
                this.loadingResults = false;
                this.displayingResults = true;

                this.resetForm();
            }.bind(this)).error(function(data, status, request) {
                this.shortenedUrl = data.error;
                this.loadingResults = false;
                this.displayingResults = true;
            }.bind(this));
        },

        validateForm: function() {
            var v = validate({
                url: this.url,
                customToken: this.customToken,
                password: this.password
            }, {
                url: { presence: true, url: true },
                customToken: {
                    length: { minimum: 2, maximum: 20 },
                    format: {
                        pattern: '[a-z0-9]+',
                        flags: 'i',
                        message: 'may only contain letters and numbers.'
                    }
                },
                password: { presence: true }
            });
          
            if (v) {      
                this.validationErrors.customToken = v.customToken;
                this.validationErrors.url = v.url;
                this.validationErrors.password = v.password;
                this.isValid = false;

                return false;
            }
          
            this.validationErrors.customToken = '';
            this.validationErrors.url = '';
            this.validationErrors.password = '';
            this.isValid = true;

            return true;
        }
    }
});