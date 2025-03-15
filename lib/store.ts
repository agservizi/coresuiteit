// Store centralizzato per la gestione dello stato dell'applicazione
import { create } from "zustand"
import { persist } from "zustand/middleware"

export interface Product {
  id: string | number
  name: string
  category: string
  provider?: string
  brand?: string
  quantity: number
  price: number
  threshold?: number
  createdAt: string
  updatedAt: string
}

export interface Sale {
  id: string | number
  date: string
  customer: Customer
  items: SaleItem[]
  provider: string
  total: number
  status: "Completata" | "In elaborazione" | "Annullata"
  paymentMethod: string
  createdAt: string
}

export interface SaleItem {
  id: string | number
  productId: string | number
  name: string
  price: number
  quantity: number
  total: number
}

export interface Customer {
  id: string | number
  name: string
  email: string
  phone: string
  address?: string
  city?: string
  zipCode?: string
  purchases?: number
  lastPurchase?: string
  totalSpent?: number
  createdAt: string
  updatedAt: string
}

export interface Supplier {
  id: string | number
  name: string
  contact: string
  email: string
  phone: string
  products: string
  lastOrder?: string
  status: "Attivo" | "In attesa" | "Inattivo"
  createdAt: string
  updatedAt: string
}

export interface User {
  id: string | number
  name: string
  email: string
  role: "admin" | "manager" | "seller"
  avatar?: string
  lastLogin?: string
  status: "active" | "inactive"
}

export interface Settings {
  companyName: string
  address: string
  phone: string
  email: string
  vat: string
  logo?: string
  theme: "light" | "dark" | "system"
  currency: string
  language: string
  notifications: {
    lowStock: boolean
    newSales: boolean
    email: boolean
  }
}

export interface Notification {
  id: string | number
  title: string
  message: string
  type: "info" | "success" | "warning" | "error"
  read: boolean
  date: string
}

interface StoreState {
  products: Product[]
  sales: Sale[]
  customers: Customer[]
  suppliers: Supplier[]
  users: User[]
  settings: Settings
  notifications: Notification[]
  isInitialized: boolean

  // Metodi per prodotti
  addProduct: (product: Omit<Product, "id" | "createdAt" | "updatedAt">) => void
  updateProduct: (product: Product) => void
  deleteProduct: (id: string | number) => void

  // Metodi per vendite
  addSale: (sale: Omit<Sale, "id" | "createdAt">) => void
  updateSale: (sale: Sale) => void
  deleteSale: (id: string | number) => void

  // Metodi per clienti
  addCustomer: (customer: Omit<Customer, "id" | "createdAt" | "updatedAt">) => void
  updateCustomer: (customer: Customer) => void
  deleteCustomer: (id: string | number) => void

  // Metodi per fornitori
  addSupplier: (supplier: Omit<Supplier, "id" | "createdAt" | "updatedAt">) => void
  updateSupplier: (supplier: Supplier) => void
  deleteSupplier: (id: string | number) => void

  // Metodi per utenti
  addUser: (user: Omit<User, "id">) => void
  updateUser: (user: User) => void
  deleteUser: (id: string | number) => void

  // Metodi per impostazioni
  updateSettings: (settings: Partial<Settings>) => void

  // Metodi per notifiche
  addNotification: (notification: Omit<Notification, "id" | "date" | "read">) => void
  markNotificationAsRead: (id: string | number) => void
  deleteNotification: (id: string | number) => void

  // Metodi avanzati per notifiche
  markAllNotificationsAsRead: () => void
  getUnreadNotificationsCount: () => number
  getNotificationsByType: (type: "info" | "success" | "warning" | "error") => Notification[]
  getRecentNotifications: (count: number) => Notification[]
  clearAllNotifications: () => void
  createSystemNotification: (notification: Omit<Notification, "id" | "date" | "read">) => void

  // Inizializzazione
  initializeStore: () => void
}

// Dati iniziali
const initialProducts: Product[] = [
  {
    id: 1,
    name: "SIM Fastweb",
    category: "SIM",
    provider: "Fastweb",
    quantity: 25,
    price: 10,
    threshold: 10,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 2,
    name: "SIM Iliad",
    category: "SIM",
    provider: "Iliad",
    quantity: 18,
    price: 9.99,
    threshold: 10,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 3,
    name: "SIM WindTre",
    category: "SIM",
    provider: "WindTre",
    quantity: 30,
    price: 10,
    threshold: 10,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 4,
    name: "SIM Sky Wifi",
    category: "SIM",
    provider: "Sky Wifi",
    quantity: 15,
    price: 15,
    threshold: 10,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 5,
    name: "SIM Pianeta Fibra",
    category: "SIM",
    provider: "Pianeta Fibra",
    quantity: 12,
    price: 12,
    threshold: 10,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 6,
    name: "iPhone 13",
    category: "Smartphone",
    brand: "Apple",
    quantity: 8,
    price: 799,
    threshold: 5,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 7,
    name: "Samsung Galaxy S22",
    category: "Smartphone",
    brand: "Samsung",
    quantity: 10,
    price: 699,
    threshold: 5,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 8,
    name: "Xiaomi Redmi Note 11",
    category: "Smartphone",
    brand: "Xiaomi",
    quantity: 15,
    price: 249,
    threshold: 5,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 9,
    name: "iPad Air",
    category: "Tablet",
    brand: "Apple",
    quantity: 5,
    price: 599,
    threshold: 3,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 10,
    name: "Decoder Sky Q",
    category: "Decoder",
    brand: "Sky",
    quantity: 7,
    price: 99,
    threshold: 3,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 11,
    name: "Modem Fastweb NeXXt",
    category: "Modem",
    brand: "Fastweb",
    quantity: 12,
    price: 49,
    threshold: 5,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 12,
    name: "Cuffie AirPods Pro",
    category: "Accessori",
    brand: "Apple",
    quantity: 20,
    price: 249,
    threshold: 8,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
  {
    id: 13,
    name: "Caricabatterie Wireless",
    category: "Accessori",
    brand: "Samsung",
    quantity: 25,
    price: 29.99,
    threshold: 8,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-01",
  },
]

const initialCustomers: Customer[] = [
  {
    id: 1,
    name: "Mario Rossi",
    email: "mario.rossi@example.com",
    phone: "+39 333 1234567",
    address: "Via Roma 123",
    city: "Milano",
    zipCode: "20100",
    purchases: 5,
    lastPurchase: "2023-06-15",
    totalSpent: 729.0,
    createdAt: "2023-05-10",
    updatedAt: "2023-06-15",
  },
  {
    id: 2,
    name: "Laura Bianchi",
    email: "laura.bianchi@example.com",
    phone: "+39 333 7654321",
    address: "Via Dante 45",
    city: "Roma",
    zipCode: "00100",
    purchases: 3,
    lastPurchase: "2023-06-14",
    totalSpent: 899.0,
    createdAt: "2023-05-15",
    updatedAt: "2023-06-14",
  },
  {
    id: 3,
    name: "Giuseppe Verdi",
    email: "giuseppe.verdi@example.com",
    phone: "+39 333 9876543",
    address: "Corso Italia 67",
    city: "Napoli",
    zipCode: "80100",
    purchases: 2,
    lastPurchase: "2023-06-14",
    totalSpent: 189.0,
    createdAt: "2023-05-20",
    updatedAt: "2023-06-14",
  },
  {
    id: 4,
    name: "Francesca Conti",
    email: "francesca.conti@example.com",
    phone: "+39 333 3456789",
    address: "Via Torino 89",
    city: "Torino",
    zipCode: "10100",
    purchases: 1,
    lastPurchase: "2023-06-13",
    totalSpent: 249.0,
    createdAt: "2023-06-01",
    updatedAt: "2023-06-13",
  },
  {
    id: 5,
    name: "Antonio Marino",
    email: "antonio.marino@example.com",
    phone: "+39 333 6789012",
    address: "Via Garibaldi 12",
    city: "Firenze",
    zipCode: "50100",
    purchases: 4,
    lastPurchase: "2023-06-12",
    totalSpent: 579.0,
    createdAt: "2023-05-05",
    updatedAt: "2023-06-12",
  },
  {
    id: 6,
    name: "Sofia Ricci",
    email: "sofia.ricci@example.com",
    phone: "+39 333 2345678",
    address: "Via Mazzini 34",
    city: "Bologna",
    zipCode: "40100",
    purchases: 2,
    lastPurchase: "2023-06-11",
    totalSpent: 909.0,
    createdAt: "2023-05-25",
    updatedAt: "2023-06-11",
  },
  {
    id: 7,
    name: "Luca Ferrari",
    email: "luca.ferrari@example.com",
    phone: "+39 333 8901234",
    address: "Corso Vittorio Emanuele 56",
    city: "Palermo",
    zipCode: "90100",
    purchases: 3,
    lastPurchase: "2023-06-10",
    totalSpent: 439.0,
    createdAt: "2023-05-18",
    updatedAt: "2023-06-10",
  },
  {
    id: 8,
    name: "Elena Martini",
    email: "elena.martini@example.com",
    phone: "+39 333 4567890",
    address: "Via Verdi 78",
    city: "Genova",
    zipCode: "16100",
    purchases: 1,
    lastPurchase: "2023-06-09",
    totalSpent: 149.0,
    createdAt: "2023-06-05",
    updatedAt: "2023-06-09",
  },
]

const initialSuppliers: Supplier[] = [
  {
    id: 1,
    name: "Fastweb S.p.A.",
    contact: "Ufficio Commerciale",
    email: "commerciale@fastweb.it",
    phone: "+39 02 45451",
    products: "SIM, Modem, Servizi",
    lastOrder: "2023-06-10",
    status: "Attivo",
    createdAt: "2023-01-01",
    updatedAt: "2023-06-10",
  },
  {
    id: 2,
    name: "Iliad Italia S.p.A.",
    contact: "Supporto Rivenditori",
    email: "rivenditori@iliad.it",
    phone: "+39 02 30377",
    products: "SIM, Servizi",
    lastOrder: "2023-06-12",
    status: "Attivo",
    createdAt: "2023-01-15",
    updatedAt: "2023-06-12",
  },
  {
    id: 3,
    name: "WindTre S.p.A.",
    contact: "Ufficio Partner",
    email: "partner@windtre.it",
    phone: "+39 06 83115",
    products: "SIM, Smartphone, Servizi",
    lastOrder: "2023-06-08",
    status: "Attivo",
    createdAt: "2023-02-01",
    updatedAt: "2023-06-08",
  },
  {
    id: 4,
    name: "Sky Italia S.r.l.",
    contact: "Divisione Rivenditori",
    email: "rivenditori@sky.it",
    phone: "+39 02 30801",
    products: "Decoder, Servizi TV, Sky Wifi",
    lastOrder: "2023-06-05",
    status: "Attivo",
    createdAt: "2023-02-15",
    updatedAt: "2023-06-05",
  },
  {
    id: 5,
    name: "Pianeta Fibra S.r.l.",
    contact: "Ufficio Commerciale",
    email: "commerciale@pianetafibra.it",
    phone: "+39 06 45209",
    products: "Servizi Internet, Modem",
    lastOrder: "2023-06-14",
    status: "Attivo",
    createdAt: "2023-03-01",
    updatedAt: "2023-06-14",
  },
  {
    id: 6,
    name: "Samsung Italia",
    contact: "Divisione Telefonia",
    email: "b2b@samsung.it",
    phone: "+39 02 921891",
    products: "Smartphone, Tablet, Accessori",
    lastOrder: "2023-06-01",
    status: "Attivo",
    createdAt: "2023-03-15",
    updatedAt: "2023-06-01",
  },
  {
    id: 7,
    name: "Apple Italia",
    contact: "Ufficio Rivenditori",
    email: "resellers@apple.com",
    phone: "+39 800 915904",
    products: "iPhone, iPad, Accessori",
    lastOrder: "2023-05-28",
    status: "In attesa",
    createdAt: "2023-04-01",
    updatedAt: "2023-05-28",
  },
  {
    id: 8,
    name: "Xiaomi Italia",
    contact: "Supporto Rivenditori",
    email: "partners@xiaomi.it",
    phone: "+39 02 94753",
    products: "Smartphone, Accessori",
    lastOrder: "2023-06-07",
    status: "Attivo",
    createdAt: "2023-04-15",
    updatedAt: "2023-06-07",
  },
]

const initialSales: Sale[] = [
  {
    id: 1,
    date: "2023-06-15",
    customer: initialCustomers[0],
    items: [
      { id: 1, productId: 2, name: "SIM Iliad", price: 9.99, quantity: 1, total: 9.99 },
      { id: 2, productId: 13, name: "Caricabatterie Wireless", price: 29.99, quantity: 4, total: 119.96 },
    ],
    provider: "Iliad",
    total: 129.95,
    status: "Completata",
    paymentMethod: "Carta di Credito",
    createdAt: "2023-06-15",
  },
  {
    id: 2,
    date: "2023-06-14",
    customer: initialCustomers[1],
    items: [
      { id: 1, productId: 3, name: "SIM WindTre", price: 10, quantity: 1, total: 10 },
      { id: 2, productId: 7, name: "Samsung Galaxy S22", price: 699, quantity: 1, total: 699 },
    ],
    provider: "WindTre",
    total: 709,
    status: "Completata",
    paymentMethod: "Contanti",
    createdAt: "2023-06-14",
  },
  {
    id: 3,
    date: "2023-06-14",
    customer: initialCustomers[2],
    items: [
      { id: 1, productId: 1, name: "SIM Fastweb", price: 10, quantity: 1, total: 10 },
      { id: 2, productId: 11, name: "Modem Fastweb NeXXt", price: 49, quantity: 1, total: 49 },
      { id: 3, productId: 13, name: "Caricabatterie Wireless", price: 29.99, quantity: 1, total: 29.99 },
    ],
    provider: "Fastweb",
    total: 88.99,
    status: "Completata",
    paymentMethod: "Bonifico",
    createdAt: "2023-06-14",
  },
  {
    id: 4,
    date: "2023-06-13",
    customer: initialCustomers[3],
    items: [
      { id: 1, productId: 10, name: "Decoder Sky Q", price: 99, quantity: 1, total: 99 },
      { id: 2, productId: 4, name: "SIM Sky Wifi", price: 15, quantity: 1, total: 15 },
      { id: 3, productId: 13, name: "Caricabatterie Wireless", price: 29.99, quantity: 1, total: 29.99 },
    ],
    provider: "Sky",
    total: 143.99,
    status: "In elaborazione",
    paymentMethod: "Carta di Credito",
    createdAt: "2023-06-13",
  },
  {
    id: 5,
    date: "2023-06-12",
    customer: initialCustomers[4],
    items: [
      { id: 1, productId: 5, name: "SIM Pianeta Fibra", price: 12, quantity: 1, total: 12 },
      { id: 2, productId: 13, name: "Caricabatterie Wireless", price: 29.99, quantity: 3, total: 89.97 },
      { id: 3, productId: 12, name: "Cuffie AirPods Pro", price: 249, quantity: 1, total: 249 },
    ],
    provider: "Pianeta Fibra",
    total: 350.97,
    status: "Completata",
    paymentMethod: "Carta di Credito",
    createdAt: "2023-06-12",
  },
  {
    id: 6,
    date: "2023-06-11",
    customer: initialCustomers[5],
    items: [
      { id: 1, productId: 2, name: "SIM Iliad", price: 9.99, quantity: 1, total: 9.99 },
      { id: 2, productId: 6, name: "iPhone 13", price: 799, quantity: 1, total: 799 },
    ],
    provider: "Iliad",
    total: 808.99,
    status: "Completata",
    paymentMethod: "Contanti",
    createdAt: "2023-06-11",
  },
  {
    id: 7,
    date: "2023-06-10",
    customer: initialCustomers[6],
    items: [
      { id: 1, productId: 1, name: "SIM Fastweb", price: 10, quantity: 1, total: 10 },
      { id: 2, productId: 11, name: "Modem Fastweb NeXXt", price: 49, quantity: 1, total: 49 },
    ],
    provider: "Fastweb",
    total: 59,
    status: "Completata",
    paymentMethod: "Carta di Credito",
    createdAt: "2023-06-10",
  },
  {
    id: 8,
    date: "2023-06-09",
    customer: initialCustomers[7],
    items: [
      { id: 1, productId: 3, name: "SIM WindTre", price: 10, quantity: 1, total: 10 },
      { id: 2, productId: 13, name: "Caricabatterie Wireless", price: 29.99, quantity: 2, total: 59.98 },
      { id: 3, productId: 12, name: "Cuffie AirPods Pro", price: 249, quantity: 1, total: 249 },
    ],
    provider: "WindTre",
    total: 318.98,
    status: "Annullata",
    paymentMethod: "Carta di Credito",
    createdAt: "2023-06-09",
  },
]

const initialUsers: User[] = [
  {
    id: 1,
    name: "Admin",
    email: "admin@telestore.it",
    role: "admin",
    avatar: "/placeholder.svg?height=36&width=36",
    lastLogin: "2023-06-16",
    status: "active",
  },
  {
    id: 2,
    name: "Marco Bianchi",
    email: "marco.bianchi@telestore.it",
    role: "manager",
    avatar: "/placeholder.svg?height=36&width=36",
    lastLogin: "2023-06-15",
    status: "active",
  },
  {
    id: 3,
    name: "Giulia Verdi",
    email: "giulia.verdi@telestore.it",
    role: "seller",
    avatar: "/placeholder.svg?height=36&width=36",
    lastLogin: "2023-06-16",
    status: "active",
  },
  {
    id: 4,
    name: "Paolo Rossi",
    email: "paolo.rossi@telestore.it",
    role: "seller",
    avatar: "/placeholder.svg?height=36&width=36",
    lastLogin: "2023-06-14",
    status: "inactive",
  },
]

const initialSettings: Settings = {
  companyName: "TeleStore S.r.l.",
  address: "Via Roma 123, 20100 Milano",
  phone: "+39 02 1234567",
  email: "info@telestore.it",
  vat: "IT12345678901",
  logo: "/placeholder.svg?height=120&width=120",
  theme: "light",
  currency: "EUR",
  language: "it",
  notifications: {
    lowStock: true,
    newSales: true,
    email: false,
  },
}

const initialNotifications: Notification[] = [
  {
    id: 1,
    title: "Scorta bassa",
    message: "SIM Fastweb sotto la soglia minima",
    type: "warning",
    read: false,
    date: "2023-06-16T09:30:00",
  },
  {
    id: 2,
    title: "Scorta bassa",
    message: "Decoder Sky Q sotto la soglia minima",
    type: "warning",
    read: false,
    date: "2023-06-16T09:30:00",
  },
  {
    id: 3,
    title: "Nuova vendita",
    message: "Nuova vendita registrata: €249.00",
    type: "success",
    read: true,
    date: "2023-06-15T14:20:00",
  },
  {
    id: 4,
    title: "Nuovo cliente",
    message: "Nuovo cliente aggiunto: Sofia Ricci",
    type: "info",
    read: true,
    date: "2023-06-14T10:45:00",
  },
]

export const useStore = create<StoreState>()(
  persist(
    (set, get) => ({
      products: [],
      sales: [],
      customers: [],
      suppliers: [],
      users: [],
      settings: initialSettings,
      notifications: [],
      isInitialized: false,

      // Inizializzazione
      initializeStore: () => {
        // Tenta di caricare le impostazioni salvate dal localStorage
        let savedSettings = initialSettings
        try {
          const savedSettingsJson = localStorage.getItem("telestore-settings")
          if (savedSettingsJson) {
            savedSettings = JSON.parse(savedSettingsJson)
          }
        } catch (error) {
          console.error("Errore nel caricamento delle impostazioni salvate:", error)
        }

        set({
          products: initialProducts,
          sales: initialSales,
          customers: initialCustomers,
          suppliers: initialSuppliers,
          users: initialUsers,
          settings: savedSettings,
          notifications: initialNotifications,
          isInitialized: true,
        })
      },

      // Metodi per prodotti
      addProduct: (product) => {
        set((state) => {
          const newProduct: Product = {
            ...product,
            id: Date.now(),
            createdAt: new Date().toISOString().split("T")[0],
            updatedAt: new Date().toISOString().split("T")[0],
          }

          // Aggiungi notifica
          const newNotification: Notification = {
            id: Date.now() + 1,
            title: "Nuovo prodotto",
            message: `Nuovo prodotto aggiunto: ${newProduct.name}`,
            type: "info",
            read: false,
            date: new Date().toISOString(),
          }

          return {
            products: [...state.products, newProduct],
            notifications: [newNotification, ...state.notifications],
          }
        })
      },

      updateProduct: (product) => {
        set((state) => {
          const updatedProduct = { ...product, updatedAt: new Date().toISOString().split("T")[0] }

          return {
            products: state.products.map((p) => (p.id === product.id ? updatedProduct : p)),
          }
        })
      },

      deleteProduct: (id) => {
        set((state) => ({
          products: state.products.filter((p) => p.id !== id),
        }))
      },

      // Metodi per vendite
      addSale: (sale) => {
        set((state) => {
          const newSale: Sale = {
            ...sale,
            id: Date.now(),
            createdAt: new Date().toISOString().split("T")[0],
          }

          // Aggiorna scorte
          const updatedProducts = state.products.map((product) => {
            const saleItem = sale.items.find((item) => item.productId === product.id)
            if (saleItem) {
              return {
                ...product,
                quantity: product.quantity - saleItem.quantity,
                updatedAt: new Date().toISOString().split("T")[0],
              }
            }
            return product
          })

          // Aggiorna cliente
          const customer = state.customers.find((c) => c.id === sale.customer.id)
          let updatedCustomers = [...state.customers]

          if (customer) {
            const updatedCustomer: Customer = {
              ...customer,
              purchases: (customer.purchases || 0) + 1,
              lastPurchase: new Date().toISOString().split("T")[0],
              totalSpent: (customer.totalSpent || 0) + sale.total,
              updatedAt: new Date().toISOString().split("T")[0],
            }

            updatedCustomers = state.customers.map((c) => (c.id === updatedCustomer.id ? updatedCustomer : c))
          }

          // Aggiungi notifica
          const newNotification: Notification = {
            id: Date.now() + 1,
            title: "Nuova vendita",
            message: `Nuova vendita registrata: €${sale.total.toFixed(2)}`,
            type: "success",
            read: false,
            date: new Date().toISOString(),
          }

          // Controlla scorte basse
          const lowStockNotifications: Notification[] = []
          updatedProducts.forEach((product) => {
            if (product.quantity <= (product.threshold || 0) && product.quantity > 0) {
              lowStockNotifications.push({
                id: Date.now() + Math.random() * 1000,
                title: "Scorta bassa",
                message: `${product.name} sotto la soglia minima (${product.quantity} rimasti)`,
                type: "warning",
                read: false,
                date: new Date().toISOString(),
              })
            } else if (product.quantity <= 0) {
              lowStockNotifications.push({
                id: Date.now() + Math.random() * 1000,
                title: "Prodotto esaurito",
                message: `${product.name} è esaurito. Riordinare immediatamente.`,
                type: "error",
                read: false,
                date: new Date().toISOString(),
              })
            }
          })

          return {
            sales: [...state.sales, newSale],
            products: updatedProducts,
            customers: updatedCustomers,
            notifications: [...lowStockNotifications, newNotification, ...state.notifications],
          }
        })
      },

      updateSale: (sale) => {
        set((state) => ({
          sales: state.sales.map((s) => (s.id === sale.id ? sale : s)),
        }))
      },

      deleteSale: (id) => {
        set((state) => ({
          sales: state.sales.filter((s) => s.id !== id),
        }))
      },

      // Metodi per clienti
      addCustomer: (customer) => {
        set((state) => {
          const newCustomer: Customer = {
            ...customer,
            id: Date.now(),
            purchases: 0,
            totalSpent: 0,
            createdAt: new Date().toISOString().split("T")[0],
            updatedAt: new Date().toISOString().split("T")[0],
          }

          // Aggiungi notifica
          const newNotification: Notification = {
            id: Date.now() + 1,
            title: "Nuovo cliente",
            message: `Nuovo cliente aggiunto: ${newCustomer.name}`,
            type: "info",
            read: false,
            date: new Date().toISOString(),
          }

          return {
            customers: [...state.customers, newCustomer],
            notifications: [newNotification, ...state.notifications],
          }
        })
      },

      updateCustomer: (customer) => {
        set((state) => {
          const updatedCustomer = { ...customer, updatedAt: new Date().toISOString().split("T")[0] }

          return {
            customers: state.customers.map((c) => (c.id === customer.id ? updatedCustomer : c)),
          }
        })
      },

      deleteCustomer: (id) => {
        set((state) => ({
          customers: state.customers.filter((c) => c.id !== id),
        }))
      },

      // Metodi per fornitori
      addSupplier: (supplier) => {
        set((state) => {
          const newSupplier: Supplier = {
            ...supplier,
            id: Date.now(),
            createdAt: new Date().toISOString().split("T")[0],
            updatedAt: new Date().toISOString().split("T")[0],
          }

          return {
            suppliers: [...state.suppliers, newSupplier],
          }
        })
      },

      updateSupplier: (supplier) => {
        set((state) => {
          const updatedSupplier = { ...supplier, updatedAt: new Date().toISOString().split("T")[0] }

          return {
            suppliers: state.suppliers.map((s) => (s.id === supplier.id ? updatedSupplier : s)),
          }
        })
      },

      deleteSupplier: (id) => {
        set((state) => ({
          suppliers: state.suppliers.filter((s) => s.id !== id),
        }))
      },

      // Metodi per utenti
      addUser: (user) => {
        set((state) => {
          const newUser: User = {
            ...user,
            id: Date.now(),
          }

          return {
            users: [...state.users, newUser],
          }
        })
      },

      updateUser: (user) => {
        set((state) => ({
          users: state.users.map((u) => (u.id === user.id ? user : u)),
        }))
      },

      deleteUser: (id) => {
        set((state) => ({
          users: state.users.filter((u) => u.id !== id),
        }))
      },

      // Metodi per impostazioni
      updateSettings: (settings: Partial<Settings>) => {
        set((state) => {
          // Gestione speciale per le notifiche per assicurarsi che vengano aggiornate correttamente
          let updatedNotifications = state.settings.notifications
          if (settings.notifications) {
            updatedNotifications = {
              ...state.settings.notifications,
              ...settings.notifications,
            }
          }

          // Crea le impostazioni aggiornate
          const updatedSettings = {
            ...state.settings,
            ...settings,
            // Assicurati che le notifiche siano sempre aggiornate correttamente
            notifications: updatedNotifications,
          }

          // Salva le impostazioni nel localStorage per persistenza
          try {
            localStorage.setItem("telestore-settings", JSON.stringify(updatedSettings))
          } catch (error) {
            console.error("Errore nel salvataggio delle impostazioni:", error)
          }

          return {
            settings: updatedSettings,
          }
        })
      },

      // Metodi per notifiche
      addNotification: (notification) => {
        set((state) => {
          const newNotification: Notification = {
            ...notification,
            id: Date.now(),
            read: false,
            date: new Date().toISOString(),
          }

          return {
            notifications: [newNotification, ...state.notifications],
          }
        })
      },

      markNotificationAsRead: (id) => {
        set((state) => ({
          notifications: state.notifications.map((n) => (n.id === id ? { ...n, read: true } : n)),
        }))
      },

      deleteNotification: (id) => {
        set((state) => ({
          notifications: state.notifications.filter((n) => n.id !== id),
        }))
      },

      markAllNotificationsAsRead: () => {
        set((state) => ({
          notifications: state.notifications.map((n) => ({ ...n, read: true })),
        }))
      },

      getUnreadNotificationsCount: () => {
        return get().notifications.filter((n) => !n.read).length
      },

      getNotificationsByType: (type) => {
        return get().notifications.filter((n) => n.type === type)
      },

      getRecentNotifications: (count) => {
        return get().notifications.slice(0, count)
      },

      clearAllNotifications: () => {
        set({ notifications: [] })
      },

      createSystemNotification: (notification) => {
        // Crea una notifica di sistema che verrà mostrata anche come notifica del browser se supportato
        const newNotification: Notification = {
          ...notification,
          id: Date.now(),
          read: false,
          date: new Date().toISOString(),
        }

        // Aggiungi la notifica allo store
        set((state) => ({
          notifications: [newNotification, ...state.notifications],
        }))

        // Se il browser supporta le notifiche, mostra anche una notifica del browser
        if ("Notification" in window && Notification.permission === "granted") {
          try {
            new Notification(notification.title, {
              body: notification.message,
              icon: "/favicon.ico",
            })
          } catch (error) {
            console.error("Errore nella creazione della notifica del browser:", error)
          }
        }
      },
    }),
    {
      name: "telestore-storage",
      partialize: (state) => ({
        products: state.products,
        sales: state.sales,
        customers: state.customers,
        suppliers: state.suppliers,
        users: state.users,
        settings: state.settings,
        notifications: state.notifications,
        isInitialized: state.isInitialized,
      }),
    },
  ),
)

