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
  import { createMutation } from "../utils/query";

  let image = $state<string>();
  let description = $state<string>("");

  const post = createMutation<void, FormData>();

  function createPost() {
    if (!image) return;
    const sentFormData = new FormData();
    sentFormData.append("text", description);
    sentFormData.append("image", image);
    $post.mutate(sentFormData);
  }
</script>

<div class="h-full">
  <button
    onclick={() => (image ? (image = "") : history.back())}
    aria-label="Retour"
    class="absolute top-0 left-0 p-4 text-3xl z-20"
  >
    <span class="icon-[tabler--arrow-narrow-left] text-ui-surface"></span>
  </button>

  <div
    class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-ui-background/50 to-transparent z-10"
  ></div>

  {#if image}
    <img src={image} alt="captured image" class="object-cover h-dvh w-full" />
    <div
      class="absolute bottom-0 left-0 w-full flex flex-col p-4 after:content-[''] after:block after:bg-gradient-to-b after:from-transparent after:to-ui-background/70 after:absolute after:w-full after:h-full after:z-1 after:top-0 after:left-0"
    >
      <textarea
        rows="4"
        placeholder="Description"
        class="input z-10 outline-none placeholder:text-ui-surface/70 bg-ui-background/60 text-ui-surface"
        bind:value={description}
      ></textarea>
      <button class="button secondary mt-4 z-10" onclick={createPost}>
        Poster
      </button>
    </div>
  {:else}
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
  {/if}
</div>
