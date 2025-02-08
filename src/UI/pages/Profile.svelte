<script lang="ts">

    export let user: {
        name: string,
    };
    console.log(user.name);
</script>

<div class="min-h-screen bg-gray-100">
    <div class="bg-white shadow-md">
        <div class="max-w-5xl mx-auto p-4">
            <div class="flex items-center space-x-4">
                <img src="./image/icon_user.png" alt="profile" class="w-24 h-24 rounded-full">
                <div>
                    <h2 class="text-2xl font-bold">{user.name}</h2>
                </div>
            </div>
            <div class="mt-4 flex space-x-8">
                <div>
                    <span class="font-bold">2</span> posts
                </div>
                <div>
                    <span class="font-bold">10</span> followers
                </div>
                    <span class="font-bold">30</span> following
                <div>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-5xl mx-auto mt-6 grid grid-cols-3 gap-4">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="" alt="Post" class="w-full h-48 object-cover">
                <div class="p-4">
                    <p class="text-gray-800">text</p>
                </div>
            </div>
    </div>
</div>