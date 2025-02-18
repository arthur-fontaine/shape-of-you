import {IClothingList} from "./ClothingList";
import {IPost} from "./Post";

export interface IUser {
    userId: number;
    name: string;
    email: string;
    isEnabled: boolean;
    weightKg: number;
    sizeCm: number;
    hipMeasurementCm: number;
    waistMeasurementCm: number;
    chestMeasurementCm: number;
    armMeasurementCm: number;
    legMeasurementCm: number;
    footMeasurementCm: number;
    isFake: boolean;
    hasFinishedOnboarding: boolean;
    role: string;
    isVerified: boolean;
    friends: IUser[];
    posts: IPost[];
    clothingLists: IClothingList[];
}