import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Badge } from "@/components/ui/badge"
import { Search, Filter, Download } from "lucide-react"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"
import Link from "next/link"

export default function InventoryPage() {
  const simCards = [
    { id: 1, name: "SIM Fastweb", category: "SIM", provider: "Fastweb", quantity: 25, price: 10 },
    { id: 2, name: "SIM Iliad", category: "SIM", provider: "Iliad", quantity: 18, price: 9.99 },
    { id: 3, name: "SIM WindTre", category: "SIM", provider: "WindTre", quantity: 30, price: 10 },
    { id: 4, name: "SIM Sky Wifi", category: "SIM", provider: "Sky Wifi", quantity: 15, price: 15 },
    { id: 5, name: "SIM Pianeta Fibra", category: "SIM", provider: "Pianeta Fibra", quantity: 12, price: 12 },
  ]

  const devices = [
    { id: 1, name: "iPhone 13", category: "Smartphone", brand: "Apple", quantity: 8, price: 799 },
    { id: 2, name: "Samsung Galaxy S22", category: "Smartphone", brand: "Samsung", quantity: 10, price: 699 },
    { id: 3, name: "Xiaomi Redmi Note 11", category: "Smartphone", brand: "Xiaomi", quantity: 15, price: 249 },
    { id: 4, name: "iPad Air", category: "Tablet", brand: "Apple", quantity: 5, price: 599 },
    { id: 5, name: "Decoder Sky Q", category: "Decoder", brand: "Sky", quantity: 7, price: 99 },
    { id: 6, name: "Modem Fastweb NeXXt", category: "Modem", brand: "Fastweb", quantity: 12, price: 49 },
    { id: 7, name: "Cuffie AirPods Pro", category: "Accessori", brand: "Apple", quantity: 20, price: 249 },
    { id: 8, name: "Caricabatterie Wireless", category: "Accessori", brand: "Samsung", quantity: 25, price: 29.99 },
  ]

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h1 className="text-3xl font-bold tracking-tight">Gestione Magazzino</h1>
        </div>

        <div className="flex flex-col sm:flex-row items-center gap-2">
          <div className="relative flex-1 w-full">
            <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input type="search" placeholder="Filtra prodotti..." className="pl-8 w-full" />
          </div>
          <div className="flex items-center gap-2 mt-2 sm:mt-0">
            <Button variant="outline" size="icon">
              <Filter className="h-4 w-4" />
              <span className="sr-only">Filtra</span>
            </Button>
            <Button variant="outline" size="icon">
              <Download className="h-4 w-4" />
              <span className="sr-only">Esporta</span>
            </Button>
          </div>
        </div>

        <Tabs defaultValue="sim" className="space-y-4">
          <TabsList className="w-full overflow-x-auto">
            <TabsTrigger value="sim">SIM</TabsTrigger>
            <TabsTrigger value="devices">Dispositivi</TabsTrigger>
          </TabsList>
          <TabsContent value="sim" className="space-y-4">
            <div className="rounded-md border overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Nome</TableHead>
                    <TableHead>Gestore</TableHead>
                    <TableHead>Quantità</TableHead>
                    <TableHead>Prezzo (€)</TableHead>
                    <TableHead>Stato</TableHead>
                    <TableHead className="text-right">Azioni</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {simCards.map((sim) => (
                    <TableRow key={sim.id}>
                      <TableCell className="font-medium">{sim.name}</TableCell>
                      <TableCell>{sim.provider}</TableCell>
                      <TableCell>{sim.quantity}</TableCell>
                      <TableCell>{sim.price.toFixed(2)}</TableCell>
                      <TableCell>
                        {sim.quantity > 10 ? (
                          <Badge className="bg-green-500">Disponibile</Badge>
                        ) : sim.quantity > 5 ? (
                          <Badge className="bg-yellow-500">Scorta Limitata</Badge>
                        ) : (
                          <Badge className="bg-red-500">Scorta Bassa</Badge>
                        )}
                      </TableCell>
                      <TableCell className="text-right">
                        <Button variant="ghost" size="sm" asChild>
                          <Link href={`/inventory/edit/${sim.id}`}>Modifica</Link>
                        </Button>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>
          </TabsContent>
          <TabsContent value="devices" className="space-y-4">
            <div className="rounded-md border overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Nome</TableHead>
                    <TableHead>Categoria</TableHead>
                    <TableHead>Marca</TableHead>
                    <TableHead>Quantità</TableHead>
                    <TableHead>Prezzo (€)</TableHead>
                    <TableHead>Stato</TableHead>
                    <TableHead className="text-right">Azioni</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {devices.map((device) => (
                    <TableRow key={device.id}>
                      <TableCell className="font-medium">{device.name}</TableCell>
                      <TableCell>{device.category}</TableCell>
                      <TableCell>{device.brand}</TableCell>
                      <TableCell>{device.quantity}</TableCell>
                      <TableCell>{device.price.toFixed(2)}</TableCell>
                      <TableCell>
                        {device.quantity > 10 ? (
                          <Badge className="bg-green-500">Disponibile</Badge>
                        ) : device.quantity > 5 ? (
                          <Badge className="bg-yellow-500">Scorta Limitata</Badge>
                        ) : (
                          <Badge className="bg-red-500">Scorta Bassa</Badge>
                        )}
                      </TableCell>
                      <TableCell className="text-right">
                        <Button variant="ghost" size="sm" asChild>
                          <Link href={`/inventory/edit/${device.id}`}>Modifica</Link>
                        </Button>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>
          </TabsContent>
        </Tabs>
      </main>
    </div>
  )
}

