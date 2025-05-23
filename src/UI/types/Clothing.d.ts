export interface IClothing {
  id: number;
  name: string;
  type: string;
  imageUrl: string;
  color: string[];
  socialRate5: number;
  ecologyRate5: number;
  measurements: IClothingMeasurements;
  dressing?: {
    comment?: string | null;
    rate?: number | null;
  } | null;
  bookmarked: boolean;
  links: IClothingLink[];
}

export interface IClothingLink {
  url: string;
  prices: IClothingPrice[];
}

export interface IClothingPrice {
  priceCts: number;
  isOnSale: boolean;
  registeredAt: string;
}

export interface IClothingMeasurements {
  chest?: number;
  waist?: number;
  length?: number;
}
