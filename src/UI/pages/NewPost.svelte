<!-- <script lang="ts">
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

<form on:submit|preventDefault={onSubmit} class="flex flex-col gap-4 h-full">
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

  <div class="hidden peer-checked/take-photo:block flex-1">
    <Camera enableCapture enableModeSwitch onCapture={console.log} captureAsInput="take-photo-image" />
  </div>

  <div class="hidden peer-checked/upload-photo:block flex-1">
    <input type="file" name="upload-photo-image" accept="image/*" />
  </div>

   <textarea name="text" placeholder="Description" class="h-32 p-2 border border-gray-300 rounded-md"></textarea>

  <button type="submit" disabled={$post.isPending} class="button">
    {#if $post.isPending}
      <div class="spinner"></div>
    {/if}
    Post
  </button>
</form> -->

<script lang="ts">
  import Camera from "../components/Camera.svelte";

  let image = $state<string>();
</script>

<div class="h-full">
  <button
    onclick={() => navigation.back()}
    class="absolute top-0 left-0 p-4 text-3xl z-10"
  >
    <span class="icon-[tabler--arrow-narrow-left] text-ui-surface"></span>
  </button>

  <Camera
    enableCapture
    enableModeSwitch
    onCapture={(img) => (image = img)}
    captureAsInput="take-photo-image"
    width={1080}
    height={1350}
    capturedImage={image}
  >
    <label>
      <span class="icon-[tabler--library-photo] text-ui-surface flex"></span>
      <input
        type="file"
        name="upload-photo-image"
        accept="image/*"
        class="hidden"
        onchange={async (e) => {
          const file = (e.target as HTMLInputElement).files?.[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
              image = event.target?.result as string;
            };
            reader.readAsDataURL(file);
          }
        }}
      />
    </label>
  </Camera>
</div>
