<script lang="ts">
    import { onMount } from 'svelte';

    let error = '';
    let lastUsername = '';
    let csrfToken = '';

    onMount(async () => {
        const response = await fetch('/api/login-data');
        const data = await response.json();
        error = data.error;
        lastUsername = data.lastUsername;
        csrfToken = data.csrfToken;
    });

    function handleSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('/login', {
            method: 'POST',
            body: formData
        }).then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        }).then(data => {
            error = data.error;
            csrfToken = data.csrfToken;
        });
    }
</script>

{#if error}
    <div class="alert alert-danger">{error}</div>
{/if}

<h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
<form on:submit={handleSubmit} method="post">
    <label for="username">Email</label>
    <input type="email" bind:value={lastUsername} name="_username" id="username" class="form-control" autocomplete="email" required autofocus>
    <label for="password">Password</label>
    <input type="password" name="_password" id="password" class="form-control" autocomplete="current-password" required>
    <input type="hidden" name="_csrf_token" bind:value={csrfToken}>
    <button class="btn btn-lg btn-background" type="submit">Sign in</button>
</form>