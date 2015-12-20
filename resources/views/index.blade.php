<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Url Shortener</title>

    <link href="//fonts.googleapis.com/css?family=Rancho" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
</head>
<body class="loading">
    <div class="container" id="app">
        <header>
            <h1>URL Shortener</h1>
        </header>

        <form @submit="shortenUrl" @submit.prevent id="url-shortener">
            <input type="text" name="customToken" placeholder="Custom Token (max: 20 chatacters)" v-model="customToken">
            <label for="customToken" class="error" v-if="validationErrors.customToken" debounce="200">@{{ validationErrors.customToken }}</label>
            <input type="text" name="url" placeholder="ex. http://google.com" debounce="200" v-model="url">
            <label for="url" class="error" v-if="validationErrors.url">@{{ validationErrors.url }}</label>
            <input type="password" v-model="password" placeholder="Password" debounce="200">
            <label for="password" class="error" v-if="validationErrors.password">@{{ validationErrors.password }}</label>
            <input type="submit" :disabled="loadingResults" :class="isValid ? 'valid' : 'not-valid'" value="Shorten Now!">
        </form>

        <div id="loading-url" v-if="loadingResults"></div>

        <section id="result"  v-if="displayingResults">
            <h2>Shortened Url</h2>
            <input type="text" v-model="shortenedUrl" disabled>
        </section>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/1.0.11/vue.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.9.0/validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.17/vue-resource.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>