<script lang="ts">
  import { createMutation } from "../utils/query";
  const search = createMutation<
    {
      name: string;
    }[],
    FormData
  >();

  function onSubmit(e: SubmitEvent) {
    const formData = new FormData(e.target as HTMLFormElement);
    if (!formData.get("search") && formData.get("image")) {
      formData.delete("search");
    }
    if (!formData.get("image") && formData.get("search")) {
      formData.delete("image");
    }
    $search.mutate(formData);
  }

  function onImageSelected(e: Event) {
    const form = (e.target as HTMLInputElement).closest("form");
    form?.submit();
  }
</script>

<form on:submit|preventDefault={onSubmit}>
  <div class="flex">
    <input type="text" name="search" placeholder="Search" class="flex-1" />
    <button type="submit"> Search </button>
    <label>
      <input
        type="file"
        name="image"
        accept="image/png, image/jpeg"
        hidden
        on:change={onImageSelected}
      />
      <button type="button"> Image </button>
    </label>
  </div>
</form>

{#if $search.isPending}
  <p>Loading...</p>
{:else if $search.isError}
  <p>Error: {$search.error.message}</p>
{:else if $search.data}
  <ul>
    {#each $search.data as item}
      <li>{item.name}</li>
    {/each}
  </ul>
{/if}
