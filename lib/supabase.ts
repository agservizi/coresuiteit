// Aggiorna l'importazione e la creazione del client Supabase
import { createClient } from "@supabase/supabase-js"

// Utilizzo delle variabili d'ambiente per la connessione a Supabase
const supabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL as string
const supabaseAnonKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY as string

// Creazione del client Supabase
export const supabase = createClient(supabaseUrl, supabaseAnonKey)

// Tipi per le tabelle del database
export type Product = {
  id: string
  name: string
  category: string
  provider?: string
  brand?: string
  quantity: number
  price: number
  threshold?: number
  created_at: string
  updated_at: string
}

export type Customer = {
  id: string
  name: string
  email: string
  phone: string
  address?: string
  city?: string
  zip_code?: string
  purchases?: number
  last_purchase?: string
  total_spent?: number
  created_at: string
  updated_at: string
}

export type Supplier = {
  id: string
  name: string
  contact: string
  email: string
  phone: string
  products: string
  last_order?: string
  status: "Attivo" | "In attesa" | "Inattivo"
  created_at: string
  updated_at: string
}

export type SaleItem = {
  id: string
  sale_id: string
  product_id: string
  name: string
  price: number
  quantity: number
  total: number
}

export type Sale = {
  id: string
  date: string
  customer_id: string
  customer?: Customer
  items?: SaleItem[]
  provider: string
  total: number
  status: "Completata" | "In elaborazione" | "Annullata"
  payment_method: string
  created_at: string
}

