"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Separator } from "@/components/ui/separator"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Plus, Trash2, Save, ArrowLeft, Printer } from "lucide-react"
import Link from "next/link"
import { useStore } from "@/lib/store"
import { toast } from "@/hooks/use-toast"
import { generateReceipt } from "@/components/receipt-generator"

export default function NewSalePage() {
  const { addSale, customers } = useStore()
  const [selectedItems, setSelectedItems] = useState([
    { id: 1, name: "SIM Iliad", price: 9.99, quantity: 1, total: 9.99 },
  ])
  const [customer, setCustomer] = useState("")
  const [provider, setProvider] = useState("")
  const [paymentMethod, setPaymentMethod] = useState("Contanti")

  const addItem = () => {
    const newItem = { id: Date.now(), name: "", price: 0, quantity: 1, total: 0 }
    setSelectedItems([...selectedItems, newItem])
  }

  const removeItem = (id) => {
    setSelectedItems(selectedItems.filter((item) => item.id !== id))
  }

  const updateItem = (id, field, value) => {
    setSelectedItems(
      selectedItems.map((item) => {
        if (item.id === id) {
          const updatedItem = { ...item, [field]: value }
          if (field === "price" || field === "quantity") {
            updatedItem.total = updatedItem.price * updatedItem.quantity
          }
          return updatedItem
        }
        return item
      }),
    )
  }

  const calculateTotal = () => {
    return selectedItems.reduce((sum, item) => sum + item.total, 0)
  }

  const handleSaveVendita = () => {
    if (!customer || !provider || selectedItems.some((item) => !item.name)) {
      toast({
        title: "Errore",
        description: "Compila tutti i campi obbligatori prima di salvare la vendita.",
        variant: "destructive",
      })
      return
    }

    const selectedCustomer = customers.find((c) => c.id.toString() === customer)

    if (!selectedCustomer) {
      toast({
        title: "Errore",
        description: "Seleziona un cliente valido.",
        variant: "destructive",
      })
      return
    }

    const newSale = {
      date: new Date().toISOString().split("T")[0],
      customer: selectedCustomer,
      items: selectedItems.map((item) => ({
        id: item.id,
        productId: item.id,
        name: item.name,
        price: item.price,
        quantity: item.quantity,
        total: item.total,
      })),
      provider: provider,
      total: calculateTotal(),
      status: "Completata",
      paymentMethod: paymentMethod,
    }

    addSale(newSale)

    toast({
      title: "Vendita salvata",
      description: "La vendita è stata registrata con successo.",
      variant: "success",
    })

    // Reimposta il form
    setSelectedItems([{ id: Date.now(), name: "", price: 0, quantity: 1, total: 0 }])
    setCustomer("")
    setProvider("")
  }

  const handlePrintReceipt = () => {
    try {
      if (!customer || !provider || selectedItems.some((item) => !item.name)) {
        toast({
          title: "Errore",
          description: "Compila tutti i campi obbligatori prima di generare lo scontrino.",
          variant: "destructive",
        })
        return
      }

      const selectedCustomer = customers.find((c) => c.id.toString() === customer)

      if (!selectedCustomer) {
        toast({
          title: "Errore",
          description: "Seleziona un cliente valido.",
          variant: "destructive",
        })
        return
      }

      generateReceipt({
        items: selectedItems,
        customerName: selectedCustomer.name,
        provider: provider,
        paymentMethod: paymentMethod,
        total: calculateTotal(),
      })

      toast({
        title: "Scontrino generato",
        description: "Lo scontrino è stato generato con successo.",
        variant: "success",
      })
    } catch (error) {
      console.error("Errore nella generazione dello scontrino:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore durante la generazione dello scontrino.",
        variant: "destructive",
      })
    }
  }

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex items-center gap-4">
          <Button variant="outline" size="icon" asChild>
            <Link href="/sales">
              <ArrowLeft className="h-4 w-4" />
              <span className="sr-only">Indietro</span>
            </Link>
          </Button>
          <h1 className="text-3xl font-bold tracking-tight">Nuova Vendita</h1>
        </div>

        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          <Card className="col-span-2">
            <CardHeader>
              <CardTitle>Dettagli Vendita</CardTitle>
              <CardDescription>Inserisci i dettagli della vendita</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="customer">Cliente</Label>
                  <Select value={customer} onValueChange={setCustomer}>
                    <SelectTrigger id="customer">
                      <SelectValue placeholder="Seleziona cliente" />
                    </SelectTrigger>
                    <SelectContent>
                      {customers.map((c) => (
                        <SelectItem key={c.id} value={c.id.toString()}>
                          {c.name}
                        </SelectItem>
                      ))}
                      <SelectItem value="nuovo-cliente">Nuovo Cliente...</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="provider">Gestore Principale</Label>
                  <Select value={provider} onValueChange={setProvider}>
                    <SelectTrigger id="provider">
                      <SelectValue placeholder="Seleziona gestore" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Fastweb">Fastweb</SelectItem>
                      <SelectItem value="Iliad">Iliad</SelectItem>
                      <SelectItem value="WindTre">WindTre</SelectItem>
                      <SelectItem value="Sky Wifi">Sky Wifi</SelectItem>
                      <SelectItem value="Sky TV">Sky TV</SelectItem>
                      <SelectItem value="Pianeta Fibra">Pianeta Fibra</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="payment">Metodo di Pagamento</Label>
                <Select value={paymentMethod} onValueChange={setPaymentMethod}>
                  <SelectTrigger id="payment">
                    <SelectValue placeholder="Seleziona metodo di pagamento" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Contanti">Contanti</SelectItem>
                    <SelectItem value="Carta di Credito">Carta di Credito</SelectItem>
                    <SelectItem value="Bonifico">Bonifico</SelectItem>
                    <SelectItem value="PayPal">PayPal</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <Separator />

              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <Label>Articoli</Label>
                  <Button variant="outline" size="sm" onClick={addItem}>
                    <Plus className="mr-2 h-4 w-4" />
                    Aggiungi Articolo
                  </Button>
                </div>

                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Articolo</TableHead>
                      <TableHead>Prezzo (€)</TableHead>
                      <TableHead>Quantità</TableHead>
                      <TableHead>Totale (€)</TableHead>
                      <TableHead></TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {selectedItems.map((item) => (
                      <TableRow key={item.id}>
                        <TableCell>
                          <Select value={item.name} onValueChange={(value) => updateItem(item.id, "name", value)}>
                            <SelectTrigger>
                              <SelectValue placeholder="Seleziona articolo" />
                            </SelectTrigger>
                            <SelectContent>
                              <SelectItem value="SIM Fastweb">SIM Fastweb</SelectItem>
                              <SelectItem value="SIM Iliad">SIM Iliad</SelectItem>
                              <SelectItem value="SIM WindTre">SIM WindTre</SelectItem>
                              <SelectItem value="iPhone 13">iPhone 13</SelectItem>
                              <SelectItem value="Samsung Galaxy S22">Samsung Galaxy S22</SelectItem>
                              <SelectItem value="Modem Fastweb NeXXt">Modem Fastweb NeXXt</SelectItem>
                              <SelectItem value="Attivazione Fibra">Attivazione Fibra</SelectItem>
                              <SelectItem value="Ricarica SIM">Ricarica SIM</SelectItem>
                            </SelectContent>
                          </Select>
                        </TableCell>
                        <TableCell>
                          <Input
                            type="number"
                            value={item.price}
                            onChange={(e) => updateItem(item.id, "price", Number.parseFloat(e.target.value))}
                            min="0"
                            step="0.01"
                          />
                        </TableCell>
                        <TableCell>
                          <Input
                            type="number"
                            value={item.quantity}
                            onChange={(e) => updateItem(item.id, "quantity", Number.parseInt(e.target.value))}
                            min="1"
                          />
                        </TableCell>
                        <TableCell>{item.total.toFixed(2)}</TableCell>
                        <TableCell>
                          <Button variant="ghost" size="icon" onClick={() => removeItem(item.id)}>
                            <Trash2 className="h-4 w-4" />
                            <span className="sr-only">Rimuovi</span>
                          </Button>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </div>
            </CardContent>
            <CardFooter className="flex justify-between">
              <div className="text-2xl font-bold">Totale: €{calculateTotal().toFixed(2)}</div>
              <div className="flex gap-2">
                <Button variant="outline" onClick={handlePrintReceipt}>
                  <Printer className="mr-2 h-4 w-4" />
                  Stampa Scontrino
                </Button>
                <Button onClick={handleSaveVendita}>
                  <Save className="mr-2 h-4 w-4" />
                  Salva Vendita
                </Button>
              </div>
            </CardFooter>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Riepilogo</CardTitle>
              <CardDescription>Dettagli della vendita</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Cliente:</span>
                  <span>
                    {customer
                      ? customers.find((c) => c.id.toString() === customer)?.name || customer
                      : "Non selezionato"}
                  </span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Gestore:</span>
                  <span>{provider || "Non selezionato"}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Pagamento:</span>
                  <span>{paymentMethod}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Articoli:</span>
                  <span>{selectedItems.length}</span>
                </div>
                <Separator />
                <div className="flex justify-between font-medium">
                  <span>Totale:</span>
                  <span>€{calculateTotal().toFixed(2)}</span>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </main>
    </div>
  )
}

