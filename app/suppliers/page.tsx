import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Search, Filter, Download } from "lucide-react"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"
import Link from "next/link"

export default function SuppliersPage() {
  const suppliers = [
    {
      id: 1,
      name: "Fastweb S.p.A.",
      contact: "Ufficio Commerciale",
      email: "commerciale@fastweb.it",
      phone: "+39 02 45451",
      products: "SIM, Modem, Servizi",
      lastOrder: "2023-06-10",
      status: "Attivo",
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
    },
  ]

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h1 className="text-3xl font-bold tracking-tight">Gestione Fornitori</h1>
        </div>

        <div className="flex flex-col sm:flex-row items-center gap-2">
          <div className="relative flex-1 w-full">
            <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input type="search" placeholder="Filtra fornitori..." className="pl-8 w-full" />
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

        <div className="rounded-md border overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Nome</TableHead>
                <TableHead>Contatto</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Telefono</TableHead>
                <TableHead>Prodotti</TableHead>
                <TableHead>Ultimo Ordine</TableHead>
                <TableHead>Stato</TableHead>
                <TableHead className="text-right">Azioni</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {suppliers.map((supplier) => (
                <TableRow key={supplier.id}>
                  <TableCell className="font-medium">{supplier.name}</TableCell>
                  <TableCell>{supplier.contact}</TableCell>
                  <TableCell>{supplier.email}</TableCell>
                  <TableCell>{supplier.phone}</TableCell>
                  <TableCell>{supplier.products}</TableCell>
                  <TableCell>{supplier.lastOrder}</TableCell>
                  <TableCell>
                    {supplier.status === "Attivo" ? (
                      <Badge className="bg-green-500">Attivo</Badge>
                    ) : (
                      <Badge className="bg-yellow-500">In attesa</Badge>
                    )}
                  </TableCell>
                  <TableCell className="text-right">
                    <Button variant="ghost" size="sm" asChild>
                      <Link href={`/suppliers/${supplier.id}`}>Dettagli</Link>
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
      </main>
    </div>
  )
}

