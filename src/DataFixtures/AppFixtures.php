<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\AdminNotification;
use App\Entity\Clothing;
use App\Entity\ClothingLink;
use App\Entity\ClothingList;
use App\Entity\ClothingPrice;
use App\Entity\DressingPiece;
use App\Entity\Interaction;
use App\Entity\Post;
use App\Entity\PostRate;
use App\Entity\User;
use App\Enum\ClothingType;
use App\Enum\Color;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadClothing($manager);
        $this->loadClothingLinks($manager);
        $this->loadClothingLists($manager);
        $this->loadClothingPrices($manager);
        $this->loadDressingPieces($manager);
        $this->loadInteractions($manager);
        $this->loadPosts($manager);
        $this->loadPostRates($manager);
        $this->loadUserFriends($manager);

        $manager->flush();

        // This must be done after the flush because it depends on the users being persisted
        $this->loadAdminNotifications($manager);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setName('User ' . $i);
            $user->setEmail('user' . $i . '@example.com');
            $user->setEnabled(true);
            $user->setWeightKg(rand(60, 100));
            $user->setSizeCm(rand(150, 200));
            $user->setHipMeasurementCm(rand(80, 120));
            $user->setChestMeasurementCm(rand(80, 120));
            $user->setWaistMeasurementCm(rand(60, 100));
            $user->setArmMeasurementCm(rand(50, 70));
            $user->setLegMeasurementCm(rand(70, 100));
            $user->setFootMeasurementCm(rand(20, 30));
            $user->setFake(false);
            $user->setPassword('password');
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
    }

    private function loadAdminNotifications(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $notification = new AdminNotification();
            $notification->setNotification([
                'message' => 'Notification ' . $i,
                'user' => $this->getReference('user_' . $i, User::class)->getId()
            ]);
            $manager->persist($notification);
        }
    }

    private function loadClothing(ObjectManager $manager)
    {
        $clothingTypes = ClothingType::cases();
        $colors = Color::cases();

        for ($i = 1; $i <= 100; $i++) {
            $clothing = new Clothing();
            $clothing->setName('Clothing ' . $i);
            $clothing->setType($clothingTypes[array_rand($clothingTypes)]);
            $clothing->setColor([$colors[array_rand($colors)]]);
            $clothing->setMeasurements([
                'chest' => rand(80, 120),
                'waist' => rand(60, 100),
                'length' => rand(50, 90)
            ]);
            $clothing->setSocialRate5(rand(1, 5));
            $clothing->setEcologyRate5(rand(1, 5));
            $clothing->setImageUrl('https://picsum.photos/id/' . $i . '/150');
            $manager->persist($clothing);
            $this->addReference('clothing_' . $i, $clothing);
        }
    }

    private function loadClothingLinks(ObjectManager $manager)
    {
        for ($i = 1; $i <= 100; $i++) {
            $clothingLink = new ClothingLink();
            $clothingLink->setUrl('https://example.com/clothing/' . $i);
            $clothingLink->setClothing($this->getReference('clothing_' . $i, Clothing::class));
            $this->addReference('clothing_link_' . $i, $clothingLink);
            $manager->persist($clothingLink);
        }
    }

    private function loadClothingLists(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            // Bookmark list
            $bookmarkList = new ClothingList();
            $bookmarkList->setName('Bookmarks ' . $i);
            $bookmarkList->setCreator($this->getReference('user_' . $i, User::class));
            $bookmarkList->setBookmarkList(true);
            for ($j = 1; $j <= 5; $j++) {
                $bookmarkList->addClothing($this->getReference('clothing_' . (($i - 1) * 10 + $j), Clothing::class));
            }
            $manager->persist($bookmarkList);
            $this->addReference('bookmark_list_' . $i, $bookmarkList);

            // Non-bookmark list
            $nonBookmarkList = new ClothingList();
            $nonBookmarkList->setName('Collection ' . $i);
            $nonBookmarkList->setCreator($this->getReference('user_' . $i, User::class));
            $nonBookmarkList->setBookmarkList(false);
            for ($j = 6; $j <= 10; $j++) {
                $nonBookmarkList->addClothing($this->getReference('clothing_' . (($i - 1) * 10 + $j), Clothing::class));
            }
            $manager->persist($nonBookmarkList);
            $this->addReference('clothing_list_' . $i, $nonBookmarkList);
        }
    }

    private function loadClothingPrices(ObjectManager $manager)
    {
        for ($i = 1; $i <= 100; $i++) {
            $clothingPrice = new ClothingPrice();
            $clothingPrice->setPriceCts((1999 + $i * 100));
            $clothingPrice->setOnSale($i % 2 === 0);
            $clothingPrice->setRegisteredAt(new \DateTimeImmutable());
            $clothingPrice->setLink($this->getReference('clothing_link_' . $i, ClothingLink::class));
            $manager->persist($clothingPrice);
        }
    }

    private function loadDressingPieces(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $dressingPiece = new DressingPiece();
            $dressingPiece->setOwner($this->getReference('user_' . $i, User::class));
            $dressingPiece->setRate10(rand(1, 10));
            $dressingPiece->setComment('This is a comment for outfit ' . $i);
            $dressingPiece->setClothing($this->getReference('clothing_' . $i, Clothing::class));
            $manager->persist($dressingPiece);
        }
    }

    private function loadInteractions(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $interaction = new Interaction();
            $interaction->setOriginUser($this->getReference('user_' . $i, User::class));
            $interaction->setInteraction([
                'type' => 'like',
                'target' => 'post_' . $i
            ]);
            $manager->persist($interaction);
        }
    }

    private function loadPosts(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $post = new Post();
            $post->setAuthor($this->getReference('user_' . $i, User::class));
            $post->setText('This is the content of post ' . $i);
            $post->setPostedAt(new \DateTimeImmutable());

            $numClothings = rand(0, 3);
            for ($j = 0; $j < $numClothings; $j++) {
                $clothing = $this->getReference('clothing_' . rand(1, 100), Clothing::class);
                $post->addFeaturedClothing($clothing);
            }

            $post->setMediaUrls($post->getFeaturedClothings()->map(function ($clothing) {
                return $clothing->getImageUrl();
            })->toArray());

            $manager->persist($post);
            $this->addReference('post_' . $i, $post);
        }
    }

    private function loadPostRates(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $postRate = new PostRate();
            $postRate->setRater($this->getReference('user_' . $i, User::class));
            $postRate->setPost($this->getReference('post_' . $i, Post::class));
            $postRate->setRate10(rand(1, 10));
            $manager->persist($postRate);
        }
    }

    private function loadUserFriends(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = $this->getReference('user_' . $i, User::class);
            $numFriends = rand(0, 3);
            for ($j = 0; $j < $numFriends; $j++) {
                $friendId = rand(1, 10);
                if ($friendId !== $i) {
                    $friend = $this->getReference('user_' . $friendId, User::class);
                    $user->addFriend($friend);
                }
            }
            $manager->persist($user);
        }
    }
}
