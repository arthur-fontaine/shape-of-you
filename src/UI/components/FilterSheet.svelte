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
    
    <Sheet.Footer>
      <button 
        type="button" 
        class="text-sm text-primary underline {hasFilters ? 'visible' : 'invisible'}"
        onclick={onClear}
      >
        Effacer les filtres
      </button>
    </Sheet.Footer>
  </Sheet.Content>
</Sheet.Root>