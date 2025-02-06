<script lang="ts">
  import Camera from "../components/Camera.svelte";
  import { createMutation } from "../utils/query";
  const post = createMutation();

  function onSubmit(e: SubmitEvent) {
    const formData = new FormData(e.target as HTMLFormElement);
    const sentFormData = new FormData();
    sentFormData.append("text", formData.get("text") as string);
    console.log(formData.get("status"));
    if (formData.get("status") === "take-photo") {
      const image = formData.get("take-photo-image") as string;
      sentFormData.append("image", image);
    } else {
      sentFormData.append("image", formData.get("upload-photo-image") as Blob);
    }
    $post.mutate(sentFormData);
  }
</script>

<form on:submit|preventDefault={onSubmit}>
  <!-- MENU -->
  <input
    id="take-photo"
    class="peer/take-photo"
    type="radio"
    name="status"
    value="take-photo"
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
    value="upload-photo"
  />
  <label for="upload-photo" class="peer-checked/upload-photo:text-sky-500">
    Upload photo
  </label>
  <!-- /MENU -->

  <div class="hidden peer-checked/take-photo:block">
    <Camera enableCapture enableModeSwitch onCapture={console.log} captureAsInput="take-photo-image" />
  </div>

  <div class="hidden peer-checked/upload-photo:block">
    <input type="file" name="upload-photo-image" accept="image/*" />
  </div>

  <input type="text" name="text" placeholder="Description" />

  <button type="submit" disabled={$post.isPending}>
    {#if $post.isPending}
      <div class="spinner"></div>
    {/if}
    Post
  </button>
</form>
