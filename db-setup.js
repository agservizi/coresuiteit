// Utilizzo delle variabili d'ambiente per la connessione a Supabase
const supabaseUrl = process.env.SUPABASE_URL
const supabaseKey = process.env.SUPABASE_SERVICE_ROLE_KEY

// Verifica che le variabili d'ambiente siano definite
if (!supabaseUrl || !supabaseKey) {
  console.error(
    "Mancano le variabili d'ambiente per Supabase. Assicurati di aver configurato SUPABASE_URL e SUPABASE_SERVICE_ROLE_KEY",
  )
  process.exit(1)
}

import { createClient } from "@supabase/supabase-js"

// Creazione del client Supabase con la chiave di servizio per avere accesso completo
const supabase = createClient(supabaseUrl, supabaseKey)

async function setupDatabase() {
  console.log("Inizializzazione del database...")

  try {
    // Creazione della tabella dei prodotti
    console.log("Creazione della tabella products...")
    const { error: productsError } = await supabase.rpc("create_table_if_not_exists", {
      table_name: "products",
      table_definition: `
        id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
        name text NOT NULL,
        category text NOT NULL,
        provider text,
        brand text,
        quantity integer NOT NULL DEFAULT 0,
        price numeric(10,2) NOT NULL,
        threshold integer,
        created_at timestamp with time zone DEFAULT now(),
        updated_at timestamp with time zone DEFAULT now()
      `,
    })

    if (productsError) throw productsError

    // Creazione della tabella dei clienti
    console.log("Creazione della tabella customers...")
    const { error: customersError } = await supabase.rpc("create_table_if_not_exists", {
      table_name: "customers",
      table_definition: `
        id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
        name text NOT NULL,
        email text NOT NULL,
        phone text NOT NULL,
        address text,
        city text,
        zip_code text,
        purchases integer DEFAULT 0,
        last_purchase date,
        total_spent numeric(10,2) DEFAULT 0,
        created_at timestamp with time zone DEFAULT now(),
        updated_at timestamp with time zone DEFAULT now()
      `,
    })

    if (customersError) throw customersError

    // Creazione della tabella dei fornitori
    console.log("Creazione della tabella suppliers...")
    const { error: suppliersError } = await supabase.rpc("create_table_if_not_exists", {
      table_name: "suppliers",
      table_definition: `
        id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
        name text NOT NULL,
        contact text NOT NULL,
        email text NOT NULL,
        phone text NOT NULL,
        products text NOT NULL,
        last_order date,
        status text NOT NULL CHECK (status IN ('Attivo', 'In attesa', 'Inattivo')),
        created_at timestamp with time zone DEFAULT now(),
        updated_at timestamp with time zone DEFAULT now()
      `,
    })

    if (suppliersError) throw suppliersError

    // Creazione della tabella delle vendite
    console.log("Creazione della tabella sales...")
    const { error: salesError } = await supabase.rpc("create_table_if_not_exists", {
      table_name: "sales",
      table_definition: `
        id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
        date date NOT NULL,
        customer_id uuid REFERENCES customers(id),
        provider text NOT NULL,
        total numeric(10,2) NOT NULL,
        status text NOT NULL CHECK (status IN ('Completata', 'In elaborazione', 'Annullata')),
        payment_method text NOT NULL,
        created_at timestamp with time zone DEFAULT now()
      `,
    })

    if (salesError) throw salesError

    // Creazione della tabella degli articoli delle vendite
    console.log("Creazione della tabella sale_items...")
    const { error: saleItemsError } = await supabase.rpc("create_table_if_not_exists", {
      table_name: "sale_items",
      table_definition: `
        id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
        sale_id uuid REFERENCES sales(id) ON DELETE CASCADE,
        product_id uuid REFERENCES products(id),
        name text NOT NULL,
        price numeric(10,2) NOT NULL,
        quantity integer NOT NULL,
        total numeric(10,2) NOT NULL
      `,
    })

    if (saleItemsError) throw saleItemsError

    // Creazione della tabella delle notifiche
    console.log("Creazione della tabella notifications...")
    const { error: notificationsError } = await supabase.rpc("create_table_if_not_exists", {
      table_name: "notifications",
      table_definition: `
        id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
        title text NOT NULL,
        message text NOT NULL,
        type text NOT NULL CHECK (type IN ('info', 'success', 'warning', 'error')),
        read boolean NOT NULL DEFAULT false,
        date timestamp with time zone DEFAULT now()
      `,
    })

    if (notificationsError) throw notificationsError

    console.log("Database inizializzato con successo!")

    // Inserimento di dati di esempio
    console.log("Inserimento di dati di esempio...")

    // Inserimento di clienti di esempio
    const { error: insertCustomersError } = await supabase.from("customers").insert([
      {
        name: "Mario Rossi",
        email: "mario.rossi@example.com",
        phone: "+39 333 1234567",
        address: "Via Roma 123",
        city: "Milano",
        zip_code: "20100",
        purchases: 5,
        last_purchase: "2023-06-15",
        total_spent: 729.0,
      },
      {
        name: "Laura Bianchi",
        email: "laura.bianchi@example.com",
        phone: "+39 333 7654321",
        address: "Via Dante 45",
        city: "Roma",
        zip_code: "00100",
        purchases: 3,
        last_purchase: "2023-06-14",
        total_spent: 899.0,
      },
      {
        name: "Giuseppe Verdi",
        email: "giuseppe.verdi@example.com",
        phone: "+39 333 9876543",
        address: "Corso Italia 67",
        city: "Napoli",
        zip_code: "80100",
        purchases: 2,
        last_purchase: "2023-06-14",
        total_spent: 189.0,
      },
    ])

    if (insertCustomersError) throw insertCustomersError

    // Inserimento di prodotti di esempio
    const { error: insertProductsError } = await supabase.from("products").insert([
      {
        name: "SIM Fastweb",
        category: "SIM",
        provider: "Fastweb",
        quantity: 25,
        price: 10,
        threshold: 10,
      },
      {
        name: "SIM Iliad",
        category: "SIM",
        provider: "Iliad",
        quantity: 18,
        price: 9.99,
        threshold: 10,
      },
      {
        name: "SIM WindTre",
        category: "SIM",
        provider: "WindTre",
        quantity: 30,
        price: 10,
        threshold: 10,
      },
      {
        name: "iPhone 13",
        category: "Smartphone",
        brand: "Apple",
        quantity: 8,
        price: 799,
        threshold: 5,
      },
      {
        name: "Samsung Galaxy S22",
        category: "Smartphone",
        brand: "Samsung",
        quantity: 10,
        price: 699,
        threshold: 5,
      },
    ])

    if (insertProductsError) throw insertProductsError

    // Inserimento di fornitori di esempio
    const { error: insertSuppliersError } = await supabase.from("suppliers").insert([
      {
        name: "Fastweb S.p.A.",
        contact: "Ufficio Commerciale",
        email: "commerciale@fastweb.it",
        phone: "+39 02 45451",
        products: "SIM, Modem, Servizi",
        last_order: "2023-06-10",
        status: "Attivo",
      },
      {
        name: "Iliad Italia S.p.A.",
        contact: "Supporto Rivenditori",
        email: "rivenditori@iliad.it",
        phone: "+39 02 30377",
        products: "SIM, Servizi",
        last_order: "2023-06-12",
        status: "Attivo",
      },
      {
        name: "WindTre S.p.A.",
        contact: "Ufficio Partner",
        email: "partner@windtre.it",
        phone: "+39 06 83115",
        products: "SIM, Smartphone, Servizi",
        last_order: "2023-06-08",
        status: "Attivo",
      },
    ])

    if (insertSuppliersError) throw insertSuppliersError

    console.log("Dati di esempio inseriti con successo!")
  } catch (error) {
    console.error("Errore durante l'inizializzazione del database:", error)
  }
}

// Esegui la funzione di setup
setupDatabase()

