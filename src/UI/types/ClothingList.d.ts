import { IClothing } from './Clothing';
export interface IClothingList {
    clothingId: number;
    name: string;
    isBookmarked: boolean;
    clothingCollection: IClothing[];
}