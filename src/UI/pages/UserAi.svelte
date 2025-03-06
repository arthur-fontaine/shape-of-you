<script lang="ts">
  import type { IUser } from "../types/User";
  import { createMutation } from "../utils/query";
  import InfoUser from "../components/InfoUser.svelte";

  let {
    user,
  }: {
    user: IUser;
  } = $props();

  const editMoodPrompt = createMutation<
    void,
    {
      mood: string;
    }
  >("/edit-mood-prompt");

  let mood = $state("");

  function handleEdit(newMood: string) {
    $editMoodPrompt.mutate({ mood: newMood });
  }
</script>

<InfoUser {user} />
<div class="px-4">
  <div class="flex flex-col gap-4">
    <textarea
      class="input"
      rows="4"
      bind:value={mood}
      placeholder="Comment vous sentez-vous en ce moment ?"
    ></textarea>
    <button
      class="button"
      onclick={() => handleEdit(mood)}
    >
      Mettre à jour
    </button>
  </div>
</div>
