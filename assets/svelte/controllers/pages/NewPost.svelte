<script lang="ts">
  import { createMutation } from "../../../utils/query";
  const post = createMutation();

  function onSubmit(e: SubmitEvent) {
    const formData = new FormData(e.target as HTMLFormElement);
    $post.mutate(formData);
  }
</script>

<form on:submit|preventDefault={onSubmit}>
  <!-- MENU -->
  <input
    id="take-photo"
    class="peer/take-photo"
    type="radio"
    name="status"
    checked
  />
  <label for="take-photo" class="peer-checked/take-photo:text-sky-500">
    Take photo
  </label>
  <input
    id="upload-photo"
    class="peer/upload-photo"
    type="radio"
    name="status"
  />
  <label for="upload-photo" class="peer-checked/upload-photo:text-sky-500">
    Upload photo
  </label>
  <!-- /MENU -->

  <div class="hidden peer-checked/take-photo:block">TODO: Camera</div>

  <div class="hidden peer-checked/upload-photo:block">
    <input type="file" name="image" accept="image/*" />
  </div>

  <input type="text" name="text" placeholder="Description" />

  <button type="submit" disabled={$post.isPending}>
    {#if $post.isPending}
      <div class="spinner" />
    {/if}
    Post
  </button>
</form>
