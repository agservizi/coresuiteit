import { AlertTriangle } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"

export default function LowStockAlert() {
  const lowStockItems = [
    { id: 1, name: "SIM Fastweb", category: "SIM", quantity: 5, threshold: 10, supplier: "Fastweb S.p.A." },
    { id: 2, name: "SIM Iliad", category: "SIM", quantity: 3, threshold: 10, supplier: "Iliad Italia S.p.A." },
    {
      id: 3,
      name: "Smartphone Samsung Galaxy A53",
      category: "Dispositivi",
      quantity: 2,
      threshold: 5,
      supplier: "Samsung Italia",
    },
    { id: 4, name: "Cuffie Wireless Sony", category: "Accessori", quantity: 4, threshold: 8, supplier: "Sony Italia" },
    { id: 5, name: "Decoder Sky Q", category: "Dispositivi", quantity: 1, threshold: 3, supplier: "Sky Italia S.r.l." },
  ]

  return (
    <div className="space-y-4">
      {lowStockItems.map((item) => (
        <div
          key={item.id}
          className="flex items-center justify-between rounded-lg border p-4 bg-gradient-to-r from-amber-50/50 to-transparent dark:from-amber-950/20 dark:to-transparent transition-all hover:shadow-md"
        >
          <div className="flex items-start gap-4">
            <div className="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-full">
              <AlertTriangle className="h-5 w-5 text-amber-500" />
            </div>
            <div>
              <h3 className="font-medium">{item.name}</h3>
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <Badge variant="outline" className="bg-background/50">
                  {item.category}
                </Badge>
                <span>
                  Quantità: <strong className="text-red-500">{item.quantity}</strong>/{item.threshold}
                </span>
              </div>
              <p className="text-sm text-muted-foreground">Fornitore: {item.supplier}</p>
            </div>
          </div>
          <Button size="sm" className="shadow-sm hover:shadow-md transition-all">
            Riordina
          </Button>
        </div>
      ))}
    </div>
  )
}

