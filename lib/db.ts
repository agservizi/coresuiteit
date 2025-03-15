import { supabase, type Product, type Customer, type Supplier, type Sale, type SaleItem } from "./supabase"

// Funzioni per i prodotti
export async function getProducts(): Promise<Product[]> {
  const { data, error } = await supabase.from("products").select("*").order("name")

  if (error) {
    console.error("Errore nel recupero dei prodotti:", error)
    return []
  }

  return data || []
}

export async function getProduct(id: string): Promise<Product | null> {
  const { data, error } = await supabase.from("products").select("*").eq("id", id).single()

  if (error) {
    console.error(`Errore nel recupero del prodotto ${id}:`, error)
    return null
  }

  return data
}

export async function addProduct(product: Omit<Product, "id" | "created_at" | "updated_at">): Promise<Product | null> {
  const { data, error } = await supabase
    .from("products")
    .insert([
      {
        ...product,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      },
    ])
    .select()
    .single()

  if (error) {
    console.error("Errore nell'aggiunta del prodotto:", error)
    return null
  }

  return data
}

export async function updateProduct(id: string, product: Partial<Product>): Promise<Product | null> {
  const { data, error } = await supabase
    .from("products")
    .update({
      ...product,
      updated_at: new Date().toISOString(),
    })
    .eq("id", id)
    .select()
    .single()

  if (error) {
    console.error(`Errore nell'aggiornamento del prodotto ${id}:`, error)
    return null
  }

  return data
}

export async function deleteProduct(id: string): Promise<boolean> {
  const { error } = await supabase.from("products").delete().eq("id", id)

  if (error) {
    console.error(`Errore nell'eliminazione del prodotto ${id}:`, error)
    return false
  }

  return true
}

// Funzioni per i clienti
export async function getCustomers(): Promise<Customer[]> {
  const { data, error } = await supabase.from("customers").select("*").order("name")

  if (error) {
    console.error("Errore nel recupero dei clienti:", error)
    return []
  }

  return data || []
}

export async function getCustomer(id: string): Promise<Customer | null> {
  const { data, error } = await supabase.from("customers").select("*").eq("id", id).single()

  if (error) {
    console.error(`Errore nel recupero del cliente ${id}:`, error)
    return null
  }

  return data
}

export async function addCustomer(
  customer: Omit<Customer, "id" | "created_at" | "updated_at">,
): Promise<Customer | null> {
  const { data, error } = await supabase
    .from("customers")
    .insert([
      {
        ...customer,
        purchases: customer.purchases || 0,
        total_spent: customer.total_spent || 0,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      },
    ])
    .select()
    .single()

  if (error) {
    console.error("Errore nell'aggiunta del cliente:", error)
    return null
  }

  return data
}

export async function updateCustomer(id: string, customer: Partial<Customer>): Promise<Customer | null> {
  const { data, error } = await supabase
    .from("customers")
    .update({
      ...customer,
      updated_at: new Date().toISOString(),
    })
    .eq("id", id)
    .select()
    .single()

  if (error) {
    console.error(`Errore nell'aggiornamento del cliente ${id}:`, error)
    return null
  }

  return data
}

export async function deleteCustomer(id: string): Promise<boolean> {
  const { error } = await supabase.from("customers").delete().eq("id", id)

  if (error) {
    console.error(`Errore nell'eliminazione del cliente ${id}:`, error)
    return false
  }

  return true
}

// Funzioni per i fornitori
export async function getSuppliers(): Promise<Supplier[]> {
  const { data, error } = await supabase.from("suppliers").select("*").order("name")

  if (error) {
    console.error("Errore nel recupero dei fornitori:", error)
    return []
  }

  return data || []
}

export async function getSupplier(id: string): Promise<Supplier | null> {
  const { data, error } = await supabase.from("suppliers").select("*").eq("id", id).single()

  if (error) {
    console.error(`Errore nel recupero del fornitore ${id}:`, error)
    return null
  }

  return data
}

export async function addSupplier(
  supplier: Omit<Supplier, "id" | "created_at" | "updated_at">,
): Promise<Supplier | null> {
  const { data, error } = await supabase
    .from("suppliers")
    .insert([
      {
        ...supplier,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      },
    ])
    .select()
    .single()

  if (error) {
    console.error("Errore nell'aggiunta del fornitore:", error)
    return null
  }

  return data
}

export async function updateSupplier(id: string, supplier: Partial<Supplier>): Promise<Supplier | null> {
  const { data, error } = await supabase
    .from("suppliers")
    .update({
      ...supplier,
      updated_at: new Date().toISOString(),
    })
    .eq("id", id)
    .select()
    .single()

  if (error) {
    console.error(`Errore nell'aggiornamento del fornitore ${id}:`, error)
    return null
  }

  return data
}

export async function deleteSupplier(id: string): Promise<boolean> {
  const { error } = await supabase.from("suppliers").delete().eq("id", id)

  if (error) {
    console.error(`Errore nell'eliminazione del fornitore ${id}:`, error)
    return false
  }

  return true
}

// Funzioni per le vendite
export async function getSales(): Promise<Sale[]> {
  const { data, error } = await supabase
    .from("sales")
    .select(`
      *,
      customer:customers(*)
    `)
    .order("date", { ascending: false })

  if (error) {
    console.error("Errore nel recupero delle vendite:", error)
    return []
  }

  return data || []
}

export async function getSale(id: string): Promise<Sale | null> {
  const { data, error } = await supabase
    .from("sales")
    .select(`
      *,
      customer:customers(*),
      items:sale_items(*)
    `)
    .eq("id", id)
    .single()

  if (error) {
    console.error(`Errore nel recupero della vendita ${id}:`, error)
    return null
  }

  return data
}

export async function addSale(
  sale: Omit<Sale, "id" | "created_at">,
  items: Omit<SaleItem, "id" | "sale_id">[],
): Promise<Sale | null> {
  // Inizia una transazione
  const { data: saleData, error: saleError } = await supabase
    .from("sales")
    .insert([
      {
        ...sale,
        created_at: new Date().toISOString(),
      },
    ])
    .select()
    .single()

  if (saleError) {
    console.error("Errore nell'aggiunta della vendita:", saleError)
    return null
  }

  // Aggiungi gli articoli della vendita
  if (saleData) {
    const saleItems = items.map((item) => ({
      ...item,
      sale_id: saleData.id,
    }))

    const { error: itemsError } = await supabase.from("sale_items").insert(saleItems)

    if (itemsError) {
      console.error("Errore nell'aggiunta degli articoli della vendita:", itemsError)
      // Rollback manuale - elimina la vendita
      await supabase.from("sales").delete().eq("id", saleData.id)
      return null
    }

    // Aggiorna le quantità dei prodotti
    for (const item of items) {
      if (item.product_id) {
        const { data: product } = await supabase.from("products").select("quantity").eq("id", item.product_id).single()

        if (product) {
          await supabase
            .from("products")
            .update({
              quantity: Math.max(0, product.quantity - item.quantity),
              updated_at: new Date().toISOString(),
            })
            .eq("id", item.product_id)
        }
      }
    }

    // Aggiorna i dati del cliente
    if (sale.customer_id) {
      const { data: customer } = await supabase
        .from("customers")
        .select("purchases, total_spent")
        .eq("id", sale.customer_id)
        .single()

      if (customer) {
        await supabase
          .from("customers")
          .update({
            purchases: (customer.purchases || 0) + 1,
            last_purchase: sale.date,
            total_spent: (customer.total_spent || 0) + sale.total,
            updated_at: new Date().toISOString(),
          })
          .eq("id", sale.customer_id)
      }
    }

    // Recupera la vendita completa con i dati del cliente e gli articoli
    return getSale(saleData.id)
  }

  return null
}

export async function updateSale(id: string, sale: Partial<Sale>): Promise<Sale | null> {
  const { data, error } = await supabase.from("sales").update(sale).eq("id", id).select().single()

  if (error) {
    console.error(`Errore nell'aggiornamento della vendita ${id}:`, error)
    return null
  }

  return data
}

export async function deleteSale(id: string): Promise<boolean> {
  // Prima elimina gli articoli della vendita
  const { error: itemsError } = await supabase.from("sale_items").delete().eq("sale_id", id)

  if (itemsError) {
    console.error(`Errore nell'eliminazione degli articoli della vendita ${id}:`, itemsError)
    return false
  }

  // Poi elimina la vendita
  const { error } = await supabase.from("sales").delete().eq("id", id)

  if (error) {
    console.error(`Errore nell'eliminazione della vendita ${id}:`, error)
    return false
  }

  return true
}

