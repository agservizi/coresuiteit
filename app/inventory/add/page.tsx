"use client"

import type React from "react"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { ArrowLeft, Save } from "lucide-react"
import Link from "next/link"
import { useRouter } from "next/navigation"
import { addProduct } from "@/lib/db"
import { toast } from "@/hooks/use-toast"

export default function AddProductPage() {
  const router = useRouter()
  const [loading, setLoading] = useState(false)
  const [product, setProduct] = useState({
    name: "",
    category: "",
    provider: "",
    brand: "",
    quantity: 0,
    price: 0,
    threshold: 0,
  })

  const handleChange = (field: string, value: string | number) => {
    setProduct((prev) => ({ ...prev, [field]: value }))
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()

    if (!product.name || !product.category || product.price <= 0) {
      toast({
        title: "Errore",
        description: "Compila tutti i campi obbligatori.",
        variant: "destructive",
      })
      return
    }

    try {
      setLoading(true)
      const result = await addProduct(product)

      if (result) {
        toast({
          title: "Prodotto aggiunto",
          description: "Il prodotto è stato aggiunto con successo.",
          variant: "success",
        })
        router.push("/inventory")
      } else {
        throw new Error("Errore nell'aggiunta del prodotto")
      }
    } catch (error) {
      console.error("Errore nell'aggiunta del prodotto:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore nell'aggiunta del prodotto.",
        variant: "destructive",
      })
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex items-center gap-4">
          <Button variant="outline" size="icon" asChild>
            <Link href="/inventory">
              <ArrowLeft className="h-4 w-4" />
              <span className="sr-only">Indietro</span>
            </Link>
          </Button>
          <h1 className="text-3xl font-bold tracking-tight">Aggiungi Prodotto</h1>
        </div>

        <Card>
          <form onSubmit={handleSubmit}>
            <CardHeader>
              <CardTitle>Dettagli Prodotto</CardTitle>
              <CardDescription>Inserisci i dettagli del nuovo prodotto</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="name">Nome Prodotto *</Label>
                  <Input
                    id="name"
                    value={product.name}
                    onChange={(e) => handleChange("name", e.target.value)}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="category">Categoria *</Label>
                  <Select value={product.category} onValueChange={(value) => handleChange("category", value)}>
                    <SelectTrigger id="category">
                      <SelectValue placeholder="Seleziona categoria" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="SIM">SIM</SelectItem>
                      <SelectItem value="Smartphone">Smartphone</SelectItem>
                      <SelectItem value="Tablet">Tablet</SelectItem>
                      <SelectItem value="Modem">Modem</SelectItem>
                      <SelectItem value="Decoder">Decoder</SelectItem>
                      <SelectItem value="Accessori">Accessori</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="provider">Gestore</Label>
                  <Select value={product.provider} onValueChange={(value) => handleChange("provider", value)}>
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
                <div className="space-y-2">
                  <Label htmlFor="brand">Marca</Label>
                  <Input id="brand" value={product.brand} onChange={(e) => handleChange("brand", e.target.value)} />
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="quantity">Quantità *</Label>
                  <Input
                    id="quantity"
                    type="number"
                    min="0"
                    value={product.quantity}
                    onChange={(e) => handleChange("quantity", Number.parseInt(e.target.value))}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="price">Prezzo (€) *</Label>
                  <Input
                    id="price"
                    type="number"
                    min="0"
                    step="0.01"
                    value={product.price}
                    onChange={(e) => handleChange("price", Number.parseFloat(e.target.value))}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="threshold">Soglia Minima</Label>
                  <Input
                    id="threshold"
                    type="number"
                    min="0"
                    value={product.threshold}
                    onChange={(e) => handleChange("threshold", Number.parseInt(e.target.value))}
                  />
                </div>
              </div>
            </CardContent>
            <CardFooter className="flex justify-between">
              <Button variant="outline" asChild>
                <Link href="/inventory">Annulla</Link>
              </Button>
              <Button type="submit" disabled={loading}>
                {loading ? (
                  <>
                    <div className="animate-spin mr-2 h-4 w-4 border-2 border-b-transparent border-white rounded-full"></div>
                    Salvataggio...
                  </>
                ) : (
                  <>
                    <Save className="mr-2 h-4 w-4" />
                    Salva Prodotto
                  </>
                )}
              </Button>
            </CardFooter>
          </form>
        </Card>
      </main>
    </div>
  )
}

