<script lang="ts">
  import * as Sheet from "./sheet";
  
  export let open = false;
  export let title: string;
  export let onClose: () => void;
  export let onClear: () => void;
  export let hasFilters: boolean = false;
  
  function handleClose() {
    open = false;
    onClose();
  }
</script>

<Sheet.Root bind:open>
  <Sheet.Content side="bottom" on:close={handleClose}>
    <Sheet.Header>
      <Sheet.Title>{title}</Sheet.Title>
    </Sheet.Header>
    
    <div class="py-4">
      <slot />
    </div>
    
    {#if hasFilters}
      <Sheet.Footer>
        <button 
          type="button" 
          class="text-sm text-primary underline"
          onclick={onClear}
        >
          Effacer les filtres
        </button>
      </Sheet.Footer>
    {/if}
  </Sheet.Content>
</Sheet.Root>