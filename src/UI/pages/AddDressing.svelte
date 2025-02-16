<script lang="ts">
    const props: {
        clothingId: number;
    } = $props();

    const clothingId = props.clothingId;

    


</script>

<form >
    <!-- MENU -->
    <input
            id="name"
            type="text"
            name="rate"
            placeholder="rate"
    />
    <input
            id="isBookmark"
            type="text"
            name="comment"
            value="comment"
    />
    <button type="submit">
        Create
    </button>
</form>